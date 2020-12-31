<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\controllers;

use tlm\his\FatmaPharmacy\views\EresepDepoDr\{Antrian2, CetakResep, CetakResepDr, CetakStrukNew};
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
class EresepDepoDrController extends BaseController
{
    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/eresepdepodr.php#edit    the original method
     */
    public function actionEditData(): string
    {
        $noResep = Yii::$app->request->post("noResep") ?? throw new MissingPostParamException("noResep");
        $connection = Yii::$app->dbFatma;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT -- all are in use.
                a.kodePenjualan      AS kodePenjualanSebelumnya,
                b.TGL_DAFTAR         AS tanggalPendaftaran,
                e.JNS_CARABAYAR      AS jenisCaraBayar,
                e.KD_BAYAR           AS kodeBayar,
                e.KD_INST            AS kodeInstalasi,
                e.KD_POLI            AS kodePoli,
                e.KD_JENIS_CARABAYAR AS kodeJenisCaraBayar,
                e.KD_RRAWAT          AS kodeRuangRawat,
                a.kode_rm            AS kodeRekamMedis,
                d.no_antrian         AS noAntrian,
                a.nama_pasien        AS namaPasien,
                b.jenis_kelamin      AS kelamin,
                b.tanggal_lahir      AS tanggalLahir,
                b.alamat_jalan       AS alamat,
                b.no_telpon          AS noTelefon,
                e.tglResep1          AS tanggalAwalResep,
                e.tglResep2          AS tanggalAkhirResep,
                e.jenisResep         AS kodeJenisResep,
                e.namaInstansi       AS namaInstalasi,
                e.namaPoli           AS namaPoli,
                e.iter2              AS iter2,
                e.iter               AS iter1,
                e.jasapelayanan      AS jasaPelayanan,
                e.total              AS grandTotal,
                a.no_resep           AS noResep,
                e.pembayaran         AS pembayaran,
                e.dokter             AS dokter,
                a.no_daftar          AS noPendaftaran,
                e.keterangan         AS noUrut,
                ''                   AS riwayatAlergi,
                ''                   AS transfer,
                NULL                 AS daftarObat,
                NULL                 AS daftarRacik
            FROM db1.masterf_penjualan AS a
            LEFT JOIN db1.masterf_penjualandetail AS e ON e.no_resep = a.no_resep
            LEFT JOIN db1.pasien_small AS b ON b.no_rm = a.kode_rm
            LEFT JOIN db1.user AS c ON c.id = e.dokter
            LEFT JOIN db1.masterf_antrian AS d ON d.kode_penjualan = a.kodePenjualan
            WHERE a.no_resep = :noResep
            ORDER BY a.no_resep DESC
            LIMIT 1
        ";
        $params = [":noResep" => $noResep];
        $resep = $connection->createCommand($sql, $params)->queryOne();

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__."
            -- LINE: ".__LINE__."
            SELECT b.rwyt_alergi -- all are in use.
            FROM sirs.tdft_pendaftaran a
            LEFT JOIN sirs.tdet_kaj_dokter b on b.no_rm = a.norm
            WHERE a.nopendaftaran = :noPendaftaran
        ";
        $params = [":noPendaftaran" => $resep->noPendaftaran];
        $resep->riwayatAlergi = $connection->createCommand($sql, $params)->queryScalar();

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT transfer -- all are in use.
            FROM db1.masterf_penjualan
            WHERE
                no_resep = :noResep
                AND transfer != ''
            LIMIT 1
        ";
        $params = [":noResep" => $noResep];
        $resep->transfer = $connection->createCommand($sql, $params)->queryScalar();

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT -- all are in use.
                b.nama_barang AS namaObat1,
                a.kodeObat    AS kodeObat1 
            FROM db1.masterf_penjualan AS a
            LEFT JOIN db1.masterf_katalog AS b ON b.kode = a.kodeObatdr
            LEFT JOIN db1.masterf_kemasan AS c ON c.id = b.id_kemasankecil
            LEFT JOIN db1.master_signa AS d ON d.kode = a.signa
            LEFT JOIN db1.masterf_penjualandetail AS e ON e.no_resep = a.no_resep
            INNER JOIN db1.masterf_depo AS md ON md.kode = a.kode_depo
            LEFT JOIN db1.transaksif_stokkatalog AS ts ON ts.id_depo = md.id
            WHERE
                a.no_resep = :noResep
                AND a.kode_racik = ''
                AND ts.id_katalog = a.kodeObat
            ORDER BY a.no_resep DESC
        ";
        $params = [":noResep" => $noResep];
        $resep->daftarObat = $connection->createCommand($sql, $params)->queryAll();

        $resep->noDaftar1 ??= $resep->noDaftar2;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                a.id_racik     AS idRacik,
                a.nama_racik   AS namaRacik,
                a.signa        AS signa,
                a.no_racik     AS noRacik,
                a.diskon       AS diskon,
                b.nama_barang  AS namaBarang,
                a.kodeObat     AS kodeObat,
                a.ketjumlah    AS keteranganJumlah,
                a.jlhPenjualan AS jumlahPenjualan,
                c.nama_kemasan AS namaKemasan,
                a.harga        AS harga,
                d.nama         AS namaSigna
            FROM db1.masterf_penjualan AS a
            LEFT JOIN db1.masterf_katalog AS b ON b.kode = a.kodeObat
            LEFT JOIN db1.masterf_kemasan AS c ON c.id = b.id_kemasankecil
            LEFT JOIN db1.master_signa AS d ON d.kode = a.signa
            WHERE
                kode_racik != ''
                AND a.no_resep = :noResep
            ORDER BY a.kode ASC
        ";
        $params = [":noResep" => $noResep];
        $resep->daftarRacik = $connection->createCommand($sql, $params)->queryAll();

        foreach ($resep->daftarObat as $j => $obat) {
            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT -- all are in use.
                    b.nama_barang            AS namaObat2,
                    a.kodeObat               AS kodeObat2, 
                    a.jlhPenjualan           AS kuantitas,
                    a.harga                  AS hargaJual,
                    a.id_racik               AS idRacik,
                    a.keterangan_obat        AS keteranganObat,
                    a.signa1                 AS namaSigna1,
                    a.signa2                 AS namaSigna2,
                    a.signa3                 AS namaSigna3,
                    c.nama_kemasan           AS namaKemasan
                FROM db1.masterf_penjualan AS a
                LEFT JOIN db1.masterf_katalog AS b ON b.kode = a.kodeObat
                LEFT JOIN db1.masterf_kemasan AS c ON c.id = b.id_kemasankecil
                LEFT JOIN db1.master_signa AS d ON d.kode = a.signa
                LEFT JOIN db1.masterf_penjualandetail AS e ON e.no_resep = a.no_resep
                WHERE
                    a.no_resep = :noResep
                    AND a.kodeObatdr = :kodeObatDokter
                    AND a.kode_racik = ''
                ORDER BY a.no_resep DESC
            ";
            $params = [":noResep" => $noResep, ":kodeObatDokter" => $obat->kodeObatDokter];
            $obat2 = $connection->createCommand($sql, $params)->queryOne();
            $obat->namaObat2      = $obat2->namaObat2;
            $obat->kodeObat2      = $obat2->kodeObat2;
            $obat->kuantitas      = $obat2->kuantitas;
            $obat->hargaJual      = $obat2->hargaJual;
            $obat->idRacik        = $obat2->idRacik;
            $obat->keteranganObat = $obat2->keteranganObat;
            $obat->namaSigna1     = $obat2->namaSigna1;
            $obat->namaSigna2     = $obat2->namaSigna2;
            $obat->namaSigna3     = $obat2->namaSigna3;
            $obat->namaKemasan    = $obat2->namaKemasan;
        }

        return json_encode($resep);
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/eresepdepodr.php#cetak    the original method
     */
    public function actionCetak(): string
    {
        [   "noResep" => $noResep,
            "kodeRekamMedis" => $kodeRekamMedis,
            "noPendaftaran" => $noPendaftaran,
            "tanggalDaftar" => $tanggalDaftar,
            "namaPasien" => $namaPasien,
            "kelamin" => $kelamin,
            "tanggalLahir" => $tanggalLahir,
            "alamat" => $alamat,
            "noTelefon" => $noTelefon,
            "tanggalAwalResep" => $tanggalAwalResep,
            "tanggalAkhirResep" => $tanggalAkhirResep,
            "kodeJenisResep" => $kodeJenisResep,
            "dokter" => $dokter,
            "kodeBayar" => $kodeBayar,
            "jenisCaraBayar" => $jenisCaraBayar,
            "kodeJenisCaraBayar" => $kodeJenisCaraBayar,
            "namaInstalasi" => $namaInstalasi,
            "kodeInstalasi" => $kodeInstalasi,
            "namaPoli" => $namaPoli,
            "namaRuangRawat" => $namaRuangRawat,
            "kodeRuangRawat" => $kodeRuangRawat,
            "noUrut" => $pNoUrut,
            "pembayaran" => $pPembayaran,
            "kodePenjualanSebelumnya" => $kodePenjualanSebelumnya,
            "editResep" => $editResep,

            "kodeObat1" => $daftarKodeObat1,
            "kodeObat2" => $daftarKodeObat2,
            "kuantitas" => $daftarJumlah,
            "idRacik" => $daftarIdRacik,
            "namaSigna1" => $daftarNamaSigna1,
            "namaSigna2" => $daftarNamaSigna2,
            "namaSigna3" => $daftarNamaSigna3,
            "diskonObat" => $daftarDiskonObat,
            "hargaJual" => $daftarHargaJual,
            "keteranganObat" => $daftarKeteranganObat,

            // not exist in form
            "id" => $id,
            "verified" => $verified,
            "verifikasi" => $verifikasi,

            // exist but not converted yet
            "kode_signa" => $daftarKodeSigna,
            "kode_signa_racik" => $daftarKodeSignaRacik,
            "kode_obat_awal" => $daftarKodeObatAwal,
            "kode_pembungkus" => $daftarKodePembungkus,
            "qtypembungkus" => $daftarJumlahPembungkus,
            "hargapembungkus" => $daftarHargaPembungkus,
            "nm_racikan" => $daftarNamaRacikan,
            "diskonracik" => $daftarDiskonRacik,
            "numero" => $daftarNoRacik,

            // not exist in form
            // TODO: php: uncategorized: convert $_POST["xxx-$key"] to $_POST["xxx"][$key]
            // ["kode_pembungkus-$key"]
            // ["qtypembungkus-$key"]
            // ["hargapembungkus-$key"]
            // ["kode_obat-$key"]
            // ["qty-$key"]
            // ["hargasatuan-$key"]
            // ["ketjumlah-$key"]
        ] = Yii::$app->request->post();

        $operator = Yii::$app->userFatma->nama;
        $idDepo = Yii::$app->userFatma->idDepo;
        $idUser = Yii::$app->userFatma->id;
        $dateTime = Yii::$app->dateTime;
        $systemDate = $dateTime->transformFunc("systemDate");
        $toSystemDate = $dateTime->transformFunc("toSystemDate");
        $todayValSystem = $dateTime->todayVal("system");
        $nowValSystem = $dateTime->nowVal("system");
        $connection = Yii::$app->dbFatma;

        if (!$verified) {
            $myverif = "";
            if ($verifikasi) {
                $myverif = ", verifikasi = 1";
                $sql = /** @lang SQL */ "
                    -- FILE: ".__FILE__." 
                    -- LINE: ".__LINE__." 
                    UPDATE db1.masterf_penjualandetail
                    SET iter2 = iter2 + 1
                    WHERE no_resep = :noResep
                ";
                $params = [":noResep" => $noResep];
                $connection->createCommand($sql, $params)->execute();
            }

            if (!$editResep) {
                $fresep = "R" . Yii::$app->userFatma->kodeSubUnitDepo . date("Ymd");
                $urutan = $this->getCounter2($fresep);

                do {
                    $urutan = sprintf("%04d", ++$urutan);
                    $resep = $fresep . $urutan;
                    $cekResep = $this->addCounter2($resep);
                } while ($cekResep == "NO");

                $noResep = $resep;
            }

            $noUrut = $urutan ?? null;
            $this->setDataView("depo", $idDepo);

            $jasaHargaObat22 = 0;
            $hargaPembungkus = 0;
            $daftarPelayanan = [];
            $daftarTagihan = [];
            $diskonObat = 0;
            $diskonRacik = 0;
            $daftarTagihanRacik = [];

            if ($kodeRekamMedis) {
                $sql = /** @lang SQL */ "
                    -- FILE: ".__FILE__." 
                    -- LINE: ".__LINE__." 
                    DELETE FROM db1.masterf_penjualan
                    WHERE no_resep = :noResep
                ";
                $params = [":noResep" => $noResep];
                $connection->createCommand($sql, $params)->execute();

                $sql = /** @lang SQL */ "
                    -- FILE: ".__FILE__." 
                    -- LINE: ".__LINE__." 
                    DELETE FROM db1.masterf_penjualandetail
                    WHERE no_resep = :noResep
                ";
                $params = [":noResep" => $noResep];
                $connection->createCommand($sql, $params)->execute();

                $sql = /** @lang SQL */ "
                    -- FILE: ".__FILE__." 
                    -- LINE: ".__LINE__." 
                    SELECT TRUE
                    FROM db1.pasien_small
                    WHERE no_rm = :kodeRekamMedis
                    LIMIT 1
                ";
                $params = [":kodeRekamMedis" => $kodeRekamMedis];
                $cekRekamMedis = $connection->createCommand($sql, $params)->queryScalar();

                if ($cekRekamMedis) {
                    $sql = /** @lang SQL */ "
                        -- FILE: ".__FILE__." 
                        -- LINE: ".__LINE__." 
                        DELETE FROM db1.pasien_small
                        WHERE no_rm = :kodeRekamMedis
                    ";
                    $params = [":kodeRekamMedis" => $kodeRekamMedis];
                    $connection->createCommand($sql, $params)->execute();
                }

                $sql = /** @lang SQL */ "
                    -- FILE: ".__FILE__." 
                    -- LINE: ".__LINE__." 
                    INSERT INTO db1.pasien_small
                    SET
                        no_rm = :kodeRekamMedis,
                        nama = :nama,
                        no_daftar = :noPendaftaran,
                        tanggal_lahir = :tanggalLahir,
                        jenis_kelamin = :kelamin,
                        alamat_jalan = :alamatJalan,
                        no_telpon = :noTelefon
                ";
                $params = [
                    ":kodeRekamMedis" => $kodeRekamMedis,
                    ":nama" => $namaPasien,
                    ":noPendaftaran" => $noPendaftaran,
                    ":tanggalLahir" => $toSystemDate($tanggalLahir),
                    ":kelamin" => $kelamin,
                    ":alamatJalan" => $alamat,
                    ":noTelefon" => $noTelefon,
                ];
                $connection->createCommand($sql, $params)->execute();

                /* note: generate random sale-code and make sure it does not exist */
                do {
                    $s = substr(str_shuffle(str_repeat("abcdefghijklmnopqrstuvwxyz", 8)), 0, 8);
                    $kodePenjualan = "PJ" . rand(1000000, 9999999) . $s;
                    $sql = /** @lang SQL */ "
                        -- FILE: ".__FILE__." 
                        -- LINE: ".__LINE__." 
                        SELECT TRUE
                        FROM db1.masterf_penjualan
                        WHERE kodePenjualan = :kodePenjualan
                        LIMIT 1
                    ";
                    $params = [":kodePenjualan" => $kodePenjualan];
                    $cekKodePenjualan = $connection->createCommand($sql, $params)->queryScalar();
                } while ($cekKodePenjualan);

                $sql = /** @lang SQL */ "
                    -- FILE: ".__FILE__." 
                    -- LINE: ".__LINE__." 
                    DELETE FROM db1.masterf_penjualandetail
                    WHERE no_resep = :noResep
                ";
                $params = [":noResep" => $noResep];
                $connection->createCommand($sql, $params)->execute();

                $sql = /** @lang SQL */ "
                    -- FILE: ".__FILE__." 
                    -- LINE: ".__LINE__." 
                    SELECT
                        idPenjualandetail      AS idPenjualanDetail,
                        no_resep               AS noResep,
                        tglResep1              AS tanggalResep1,
                        tglResep2              AS tanggalResep2,
                        jenisResep             AS jenisResep,
                        dokter                 AS dokter,
                        pembayaran             AS pembayaran,
                        namaInstansi           AS namaInstansi,
                        namaPoli               AS namaPoli,
                        keterangan             AS keterangan,
                        totaldiskon            AS totalDiskon,
                        totalpembungkus        AS totalPembungkus,
                        jasapelayanan          AS jasaPelayanan,
                        total                  AS total,
                        bayar                  AS bayar,
                        kembali                AS kembali,
                        total_retur            AS totalRetur,
                        iter                   AS iter1,
                        iter2                  AS iter2,
                        nm_kamar               AS namaKamar,
                        KD_BAYAR               AS kodeBayar,
                        KD_INST                AS kodeInstalasi,
                        KD_POLI                AS kodePoli,
                        KD_RRAWAT              AS kodeRuangRawat,
                        KD_JENIS_CARABAYAR     AS kodeJenisCaraBayar,
                        JNS_CARABAYAR          AS jenisCaraBayar,
                        CARA_PEMBAYARAN        AS caraPembayaran,
                        CARA_PEMBAYARAN_DETAIL AS caraPembayaranDetail,
                        NOMOR_KARTU            AS noKartu,
                        TGL_DAFTAR             AS tanggalPendaftaran,
                        atasnama               AS atasNama
                    FROM db1.masterf_penjualandetail
                    WHERE no_resep = :noResep
                ";
                $params = [":noResep" => $noResep];
                $daftarDetailPenjualan = $connection->createCommand($sql, $params)->queryAll();

                $sql = /** @lang SQL */ "
                    -- FILE: ".__FILE__." 
                    -- LINE: ".__LINE__." 
                    SELECT jenisresep
                    FROM db1.masterf_jenisresep
                    WHERE kd_jenisresep = :kodeJenisResep
                    LIMIT 1
                ";
                $params = [":kodeJenisResep" => $kodeJenisResep];
                $jenisResep = $connection->createCommand($sql, $params)->queryScalar();

                if (!$daftarDetailPenjualan) {
                    $sql = /** @lang SQL */ "
                        -- FILE: ".__FILE__." 
                        -- LINE: ".__LINE__." 
                        INSERT INTO db1.masterf_penjualandetail
                        SET
                            no_resep = :noResep,
                            tgl_daftar = :tanggalDaftar,
                            tglResep1 = :tanggalAwalResep,
                            tglResep2 = :tanggalAkhirResep,
                            jenisResep = :jenisResep,
                            dokter = :dokter,
                            pembayaran = :pembayaran,
                            namaInstansi = :namaInstalasi,
                            namaPoli = :namaPoli,
                            nm_kamar = :namaKamar,
                            kd_inst = :kodeInstalasi,
                            kd_bayar = :kodeBayar,
                            kd_jenis_carabayar = :kodeJenisCaraBayar,
                            jns_carabayar = :jenisCaraBayar,
                            kd_rrawat = :kodeRuangRawat,
                            keterangan = :keterangan
                    ";
                    $params = [
                        ":noResep" => $noResep,
                        ":tanggalDaftar" => date("m/d/Y H:i:s", strtotime($tanggalDaftar)),
                        ":tanggalAwalResep" => $tanggalAwalResep,
                        ":tanggalAkhirResep" => $tanggalAkhirResep,
                        ":jenisResep" => $jenisResep,
                        ":dokter" => $dokter,
                        ":pembayaran" => $pPembayaran,
                        ":namaInstalasi" => $namaInstalasi,
                        ":namaPoli" => $namaPoli,
                        ":namaKamar" => $namaRuangRawat,
                        ":kodeInstalasi" => $kodeInstalasi,
                        ":kodeBayar" => $kodeBayar,
                        ":kodeJenisCaraBayar" => $kodeJenisCaraBayar,
                        ":jenisCaraBayar" => $jenisCaraBayar,
                        ":kodeRuangRawat" => $kodeRuangRawat,
                        ":keterangan" => $pNoUrut,
                    ];
                    $connection->createCommand($sql, $params)->execute();
                }

                $daftarTemuObat = [];
                $daftarJasaObatRacik = [];

                foreach ($daftarKodeObat1 as $key => $kodeObat) {
                    $diskon = (float) $daftarDiskonObat[$key];
                    $cekMinus = ($daftarJumlah[$key] < 0) ? 1 : 0;
                    $jumlah = abs($daftarJumlah[$key]);
                    $harga = $daftarHargaJual[$key];
                    $diskonObat += $harga * $jumlah * $diskon / 100;

                    $dataKatalog = $this->actionBridging5([
                        "fungsi" => "SELECT_KATALOG_BRG",
                        "kode" => $kodeObat
                    ]);

                    $dataKatalog[0]["KD_KATALOG"] = str_replace("]", "", $dataKatalog[0][0]) ?? 0;
                    $kodeObat2 = $daftarKodeObat2[$key] ?: $kodeObat;

                    $sql = /** @lang SQL */ "
                        -- FILE: ".__FILE__." 
                        -- LINE: ".__LINE__." 
                        SELECT
                            b.hna_item    AS hnaItem,
                            b.hp_item     AS hpItem,
                            b.hja_setting AS hjaSetting
                        FROM db1.masterf_katalog AS a
                        LEFT JOIN db1.relasif_hargaperolehan AS b ON b.id_katalog = a.kode
                        WHERE
                            a.kode = :kode
                            AND b.sts_hja != 0
                            AND b.sts_hjapb != 0
                        LIMIT 1
                    ";
                    $params = [":kode" => $kodeObat2];
                    $harga2 = $connection->createCommand($sql, $params)->queryOne();

                    $daftarTagihan[$key] = $harga2->hjaSetting * $daftarJumlah[$key];
                    $daftarKodeObat2[$key] ? $daftarPelayanan[$key] = 0 : null;

                    $noUrut++;
                    $minJumlah = $jumlah;

                    $urutanObat = $daftarKodeObatAwal[$key] ? ", urutan = '" . ($key + 1) . "'" : "";

                    if (!$kodeObat) continue;
                    $jumlahStok = ($cekMinus == 1) ? $minJumlah * (-1) : $minJumlah;

                    $sql = /** @lang SQL */ "
                        -- FILE: ".__FILE__." 
                        -- LINE: ".__LINE__." 
                        SELECT TRUE
                        FROM db1.masterf_penjualan
                        WHERE
                            no_resep = :noResep
                            AND kodeObat = :kodeObat
                        LIMIT 1
                    ";
                    $params = [":noResep" => $noResep, ":kodeObat" => $kodeObat];
                    $cekPenjualan = $connection->createCommand($sql, $params)->queryAll();

                    if ($cekPenjualan) {
                        $sql = /** @lang SQL */ "
                            -- FILE: ".__FILE__." 
                            -- LINE: ".__LINE__." 
                            UPDATE db1.masterf_penjualan
                            SET jlhPenjualan = jlhPenjualan + $jumlahStok
                            WHERE
                                no_resep = :noResep
                                AND kodeObat = :kodeObat
                        ";
                        $params = [":jumlahStok" => $jumlahStok, ":noResep" => $noResep, ":kodeObat" => $kodeObat];

                    } else {
                        $sql = /** @lang SQL */ "
                            -- FILE: ".__FILE__." 
                            -- LINE: ".__LINE__." 
                            INSERT INTO db1.masterf_penjualan
                            SET
                                kode_rm = :kodeRekamMedis,
                                no_daftar = :noPendaftaran,
                                nama_pasien = :namaPasien,
                                id_racik = :idRacik,
                                signa1 = :signa1,
                                signa2 = :signa2,
                                signa3 = :signa3,
                                harga = :harga,
                                jlhPenjualan = :jumlahPenjualan,
                                signa = :signa,
                                kodePenjualan = :kodePenjualan,
                                kodeObatdr = :kodeObatDokter,
                                kodeObat = :kodeObat,
                                tglPenjualan = :tanggalPenjualan,
                                kode_depo = :kodeDepo,
                                diskon = :diskon,
                                operator = :operator,
                                no_resep = :noResep,
                                hna = :hna,
                                hp = :hp,
                                keterangan_obat = :keteranganObat
                                $urutanObat
                                $myverif
                        ";
                        $params = [
                            ":kodeRekamMedis" => $kodeRekamMedis,
                            ":noPendaftaran" => $noPendaftaran,
                            ":namaPasien" => $namaPasien,
                            ":idRacik" => $daftarIdRacik[$key],
                            ":signa1" => $daftarNamaSigna1[$key],
                            ":signa2" => $daftarNamaSigna2[$key],
                            ":signa3" => $daftarNamaSigna3[$key],
                            ":harga" => $harga,
                            ":jumlahPenjualan" => $jumlahStok,
                            ":signa" => $daftarKodeSigna[$key],
                            ":kodePenjualan" => $kodePenjualan,
                            ":kodeObatDokter" => $kodeObat,
                            ":kodeObat" => $daftarKodeObat2[$key],
                            ":tanggalPenjualan" => $todayValSystem,
                            ":kodeDepo" => $idDepo,
                            ":diskon" => $diskon,
                            ":operator" => $operator,
                            ":noResep" => $noResep,
                            ":hna" => $harga2->hnaItem,
                            ":hp" => $harga2->hpItem,
                            ":keteranganObat" => $daftarKeteranganObat[$key],
                        ];
                    }
                    $connection->createCommand($sql, $params)->execute();

                    $sql = /** @lang SQL */ "
                        -- FILE: ".__FILE__." 
                        -- LINE: ".__LINE__." 
                        SELECT
                            id_listsigna   AS idListSigna,
                            kategori_signa AS kategoriSigna,
                            signa_name     AS namaSigna
                        FROM db1.masterf_listsigna
                        WHERE
                            kategori_signa = 'signa1'
                            AND signa_name = :namaSigna
                    ";
                    $params = [":namaSigna" => $daftarNamaSigna1[$key]];
                    $daftarSigna1 = $connection->createCommand($sql, $params)->queryAll();

                    if (!$daftarSigna1) {
                        $sql = /** @lang SQL */ "
                            -- FILE: ".__FILE__." 
                            -- LINE: ".__LINE__." 
                            INSERT INTO db1.masterf_listsigna
                            SET
                                signa_name = :namaSigna,
                                kategori_signa = 'signa1'
                        ";
                        $params = [":namaSigna" => $daftarNamaSigna1[$key]];
                        $connection->createCommand($sql, $params)->execute();
                    }

                    $sql = /** @lang SQL */ "
                        -- FILE: ".__FILE__." 
                        -- LINE: ".__LINE__." 
                        SELECT
                            id_listsigna   AS idListSigna,
                            kategori_signa AS kategoriSigna,
                            signa_name     AS namaSigna
                        FROM db1.masterf_listsigna
                        WHERE
                            kategori_signa = 'signa2'
                            AND signa_name = :namaSigna
                    ";
                    $params = [":namaSigna" => $daftarNamaSigna2[$key]];
                    $daftarSigna2 = $connection->createCommand($sql, $params)->queryAll();

                    if (!$daftarSigna2) {
                        $sql = /** @lang SQL */ "
                            -- FILE: ".__FILE__." 
                            -- LINE: ".__LINE__." 
                            INSERT INTO db1.masterf_listsigna
                            SET
                                signa_name = :namaSigna,
                                kategori_signa = 'signa2'
                        ";
                        $params = [":namaSigna" => $daftarNamaSigna2[$key]];
                        $connection->createCommand($sql, $params)->execute();
                    }

                    $sql = /** @lang SQL */ "
                        -- FILE: ".__FILE__." 
                        -- LINE: ".__LINE__." 
                        SELECT
                            id_listsigna   AS idListSigna,
                            kategori_signa AS kategoriSigna,
                            signa_name     AS namaSigna
                        FROM db1.masterf_listsigna
                        WHERE
                            kategori_signa = 'signa3'
                            AND signa_name = :namaSigna
                    ";
                    $params = [":namaSigna" => $daftarNamaSigna1[$key]];
                    $daftarSigna3 = $connection->createCommand($sql, $params)->queryAll();

                    if (!$daftarSigna3) {
                        $sql = /** @lang SQL */ "
                            -- FILE: ".__FILE__." 
                            -- LINE: ".__LINE__." 
                            INSERT INTO db1.masterf_listsigna
                            SET
                                signa_name = :namaSigna,
                                kategori_signa = 'signa3'
                        ";
                        $params = [":namaSigna" => $daftarNamaSigna3[$key]];
                        $connection->createCommand($sql, $params)->execute();
                    }

                    if ($verifikasi && $daftarKodeObat2[$key]) {
                        $sql = /** @lang SQL */ "
                            -- FILE: ".__FILE__." 
                            -- LINE: ".__LINE__." 
                            SELECT kode_stokopname
                            FROM db1.relasif_ketersediaan AS c
                            INNER JOIN db1.transaksif_stokopname AS a ON a.kode = c.kode_stokopname
                            LEFT JOIN db1.masterf_aktifasiso AS b ON b.kode = a.kode_reff
                            WHERE
                                c.id_depo = :idDepo
                                AND c.id_katalog = :idKatalog
                                AND b.status = 1
                            LIMIT 1
                        ";
                        $params = [":idDepo" => $idDepo, ":idKatalog" => $daftarKodeObat2[$key]];
                        $kodeStokOpname = $connection->createCommand($sql, $params)->queryScalar();

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
                        $params = [":idKatalog" => $daftarKodeObat2[$key], ":idDepo" => $idDepo];
                        $jumlahStokFisikBefore = (int) $connection->createCommand($sql, $params)->queryScalar();

                        if ($cekMinus == 1) {
                            $sql = /** @lang SQL */ "
                                -- FILE: ".__FILE__." 
                                -- LINE: ".__LINE__." 
                                INSERT INTO db1.relasif_ketersediaan
                                SET
                                    id_depo = :idDepo,
                                    kode_reff = :kodeRef,
                                    kode_stokopname = :kodeStokopname,
                                    kode_transaksi = 'R',
                                    kode_store = '000000',
                                    tipe_tersedia = 'penjualan',
                                    tgl_tersedia = :tanggalTersedia,
                                    no_batch = '-',
                                    tgl_expired = :tanggalKadaluarsa,
                                    id_katalog = :idKatalog,
                                    harga_item = :hargaItem,
                                    harga_perolehan = :hargaPerolehan,
                                    jumlah_masuk = :jumlahMasuk,
                                    jumlah_keluar = 0,
                                    jumlah_tersedia = :jumlahTersedia,
                                    userid_last = :idUser,
                                    status = 1,
                                    keterangan = :keterangan
                            ";
                            $params = [
                                ":idDepo" => $idDepo,
                                ":kodeRef" => $noResep,
                                ":kodeStokopname" => $kodeStokOpname,
                                ":tanggalTersedia" => $nowValSystem,
                                ":tanggalKadaluarsa" => $systemDate($todayValSystem, "+2 month"),
                                ":idKatalog" => $daftarKodeObat2[$key],
                                ":hargaItem" => $harga,
                                ":hargaPerolehan" => $harga2->hpItem,
                                ":jumlahMasuk" => $jumlah,
                                ":jumlahTersedia" => $jumlahStokFisikBefore + $jumlah,
                                ":idUser" => $idUser,
                                ":keterangan" => "Pemakaian pasien $namaPasien",
                            ];
                            $connection->createCommand($sql, $params)->execute();

                            $sql = /** @lang SQL */ "
                                -- FILE: ".__FILE__." 
                                -- LINE: ".__LINE__." 
                                UPDATE db1.transaksif_stokkatalog
                                SET
                                    jumlah_stokfisik = jumlah_stokfisik + :jumlah,
                                    jumlah_stokadm = jumlah_stokadm + :jumlah
                                WHERE
                                    id_katalog = :idKatalog
                                    AND id_depo = :idDepo
                            ";
                            $params = [":jumlah" => $jumlah, ":idKatalog" => $daftarKodeObat2[$key], ":idDepo" => $idDepo];
                            $connection->createCommand($sql, $params)->execute();

                        } else {
                            $sql = /** @lang SQL */ "
                                -- FILE: ".__FILE__." 
                                -- LINE: ".__LINE__." 
                                INSERT INTO db1.relasif_ketersediaan
                                SET
                                    id_depo = :idDepo,
                                    kode_reff = :kodeRef,
                                    kode_stokopname = :kodeStokopname,
                                    kode_transaksi = 'R',
                                    kode_store = '000000',
                                    tipe_tersedia = 'penjualan',
                                    tgl_tersedia = :tanggalTersedia,
                                    no_batch = '-',
                                    tgl_expired = :tanggalKadaluarsa,
                                    id_katalog = :idKatalog,
                                    harga_item = :hargaItem,
                                    harga_perolehan = :hargaPerolehan,
                                    jumlah_masuk = 0,
                                    jumlah_keluar = :jumlahKeluar,
                                    jumlah_tersedia = :jumlahTersedia,
                                    status = 1,
                                    userid_last = :idUser,
                                    keterangan = :keterangan
                            ";
                            $params = [
                                ":idDepo" => $idDepo,
                                ":kodeRef" => $noResep,
                                ":kodeStokopname" => $kodeStokOpname,
                                ":tanggalTersedia" => $nowValSystem,
                                ":tanggalKadaluarsa" => $systemDate($todayValSystem, "+2 month"),
                                ":idKatalog" => $daftarKodeObat2[$key],
                                ":hargaItem" => $harga,
                                ":hargaPerolehan" => $harga2->hpItem,
                                ":jumlahKeluar" => $minJumlah,
                                ":jumlahTersedia" => $jumlahStokFisikBefore - $minJumlah,
                                ":idUser" => $idUser,
                                ":keterangan" => "Pemakaian pasien $namaPasien",
                            ];
                            $connection->createCommand($sql, $params)->execute();

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
                                    keterangan       AS keterangan,
                                    check_sync       AS cekSync
                                FROM db1.transaksif_stokkatalog
                                WHERE
                                    id_depo = :idDepo
                                    AND id_katalog = :idKatalog
                            ";
                            $params = [":idDepo" => $idDepo, ":idKatalog" => $daftarKodeObat2[$key]];
                            $daftarTransaksi = $connection->createCommand($sql, $params)->queryAll();

                            if ($daftarTransaksi) {
                                $sql = /** @lang SQL */ "
                                    -- FILE: ".__FILE__." 
                                    -- LINE: ".__LINE__." 
                                    UPDATE db1.transaksif_stokkatalog
                                    SET
                                        jumlah_stokfisik = jumlah_stokfisik - :jumlah,
                                        jumlah_stokadm = jumlah_stokadm - :jumlah
                                    WHERE
                                        id_katalog = :idKatalog
                                        AND id_depo = :idDepo
                                ";
                                $params = [":jumlah" => $jumlah, ":idKatalog" => $daftarKodeObat2[$key], ":idDepo" => $idDepo];

                            } else {
                                $sql = /** @lang SQL */ "
                                    -- FILE: ".__FILE__." 
                                    -- LINE: ".__LINE__." 
                                    INSERT INTO db1.transaksif_stokkatalog
                                    SET
                                        jumlah_stokfisik = :pengurangJumlah,
                                        jumlah_stokadm = :pengurangJumlah,
                                        jumlah_itemfisik = :jumlah,
                                        id_kemasan = 0,
                                        status = 1,
                                        id_depo = :idDepo,
                                        id_katalog = :idKatalog
                                ";
                                $params = [
                                    ":pengurangJumlah" => -$jumlah,
                                    ":jumlah" => $jumlah,
                                    ":idDepo" => $idDepo,
                                    ":idKatalog" => $daftarKodeObat2[$key],
                                ];
                            }
                            $connection->createCommand($sql, $params)->execute();
                        }

                        $sql = /** @lang SQL */ "
                            -- FILE: ".__FILE__." 
                            -- LINE: ".__LINE__." 
                            UPDATE db1.masterf_ketersediaan
                            SET jlhTersedia = jlhTersedia - $minJumlah
                            WHERE
                                kodeDepo = :kodeDepo
                                AND kodeObat = :kodeObat
                        ";
                        $params = [":kodeDepo" => $idDepo, ":kodeObat" => $daftarKodeObat2[$key]];
                        $connection->createCommand($sql, $params)->execute();
                    }

                    if (!$daftarIdRacik[$key] && $kodeJenisResep != "00" && !$daftarTemuObat[$kodeObat]) {
                        $jasaHargaObat22 += 500;
                        $daftarTemuObat[$kodeObat] = "exist";

                    } elseif (!$daftarJasaObatRacik[$daftarIdRacik[$key]] && $kodeJenisResep != "00") {
                        $daftarJasaObatRacik[$daftarIdRacik[$key]] = "exist";
                        $jasaHargaObat22 += 1000;
                    }
                }

                foreach ($daftarKodePembungkus ?? [] as $key4 => $kodePembungkus) {
                    if (!$kodePembungkus) continue;
                    $sql = /** @lang SQL */ "
                        -- FILE: ".__FILE__." 
                        -- LINE: ".__LINE__." 
                        INSERT INTO db1.masterf_reseppembungkus
                        SET
                            no_resep = :noResep,
                            kd_pembungkus = :kodePembungkus,
                            jumlah = :jumlah
                    ";
                    $params = [":noResep" => $noResep, ":kodePembungkus" => $kodePembungkus, ":jumlah" => $daftarJumlahPembungkus[$key4]];
                    $connection->createCommand($sql, $params)->execute();
                    $hargaPembungkus += $daftarJumlahPembungkus[$key4] * $daftarHargaPembungkus[$key4];
                }

                foreach ($daftarNamaRacikan ?? [] as $key => $namaRacikan) {
                    $racik = date("ymd") . rand(1000, 9999);
                    $diskon = $daftarDiskonRacik[$key];

                    foreach ($_POST["kode_pembungkus-$key"] as $key4 => $kodePembungkus) {
                        $pKuantitasPembungkus = $_POST["qtypembungkus-$key"][$key4];
                        $pHargaPembungkus = $_POST["hargapembungkus-$key"][$key4];

                        if (!$kodePembungkus) continue;
                        $sql = /** @lang SQL */ "
                            -- FILE: ".__FILE__." 
                            -- LINE: ".__LINE__." 
                            INSERT INTO db1.masterf_reseppembungkus
                            SET
                                no_resep = :noResep,
                                kode_racik = :kodeRacik,
                                kd_pembungkus = :kodePembungkus,
                                jumlah = :jumlah
                        ";
                        $params = [
                            ":noResep" => $noResep,
                            ":kodeRacik" => $racik,
                            ":kodePembungkus" => $kodePembungkus,
                            ":jumlah" => $pKuantitasPembungkus,
                        ];
                        $connection->createCommand($sql, $params)->execute();

                        $hargaPembungkus += $pKuantitasPembungkus * $pHargaPembungkus;
                    }

                    foreach ($_POST["kode_obat-$key"] as $key2 => $kodeObat) {
                        $pHargaSatuan = $_POST["hargasatuan-$key"][$key2];
                        $pJumlah = $_POST["qty-$key"][$key2] ?: 1;
                        $pKeteranganJumlah = $_POST["ketjumlah-$key"][$key2];

                        $dataKatalog = $this->actionBridging5([
                            "fungsi" => "SELECT_KATALOG_BRG",
                            "kode" => $kodeObat
                        ]);

                        $sql = /** @lang SQL */ "
                            -- FILE: ".__FILE__." 
                            -- LINE: ".__LINE__." 
                            SELECT harga_jual
                            FROM db1.masterf_katalog
                            WHERE kode = :kode
                            LIMIT 1
                        ";
                        $params = [":kode" => $namaRacikan];
                        $hargaJual = $connection->createCommand($sql, $params)->queryScalar();

                        $daftarTagihanRacik[$key2] = $hargaJual * $pJumlah;
                        $diskonRacik += $pHargaSatuan * $pJumlah * $diskon / 100;
                        $dataKatalog[0]["KD_KATALOG"] ??= 0;

                        if (!$kodeObat) continue;

                        $sql = /** @lang SQL */ "
                            -- FILE: ".__FILE__." 
                            -- LINE: ".__LINE__." 
                            INSERT INTO db1.masterf_penjualan
                            SET
                                kode_rm = :kodeRekamMedis,
                                no_daftar = :noPendaftaran,
                                operator = :operator,
                                no_racik = :noRacik,
                                nama_pasien = :namaPasien,
                                harga = :harga,
                                jlhPenjualan = :jumlahPenjualan,
                                ketjumlah = :keteranganJumlah,
                                signa = :signa,
                                kodePenjualan = :kodePenjualan,
                                kodeObat = :kodeObat,
                                tglPenjualan = :tanggalPenjualan,
                                kode_depo = :kodeDepo,
                                no_resep = :noResep,
                                kode_racik = :kodeRacik,
                                diskon = :diskon,
                                nama_racik = :namaRacik
                                $myverif
                        ";
                        $params = [
                            ":kodeRekamMedis" => $kodeRekamMedis,
                            ":noPendaftaran" => $noPendaftaran,
                            ":operator" => $operator,
                            ":noRacik" => $daftarNoRacik[$key],
                            ":namaPasien" => $namaPasien,
                            ":harga" => $pHargaSatuan,
                            ":jumlahPenjualan" => $pJumlah,
                            ":keteranganJumlah" => $pKeteranganJumlah,
                            ":signa" => $daftarKodeSignaRacik[$key],
                            ":kodePenjualan" => $kodePenjualan,
                            ":kodeObat" => $kodeObat,
                            ":tanggalPenjualan" => $todayValSystem,
                            ":kodeDepo" => $idDepo,
                            ":noResep" => $noResep,
                            ":kodeRacik" => $racik,
                            ":diskon" => $diskon,
                            ":namaRacik" => $namaRacikan
                        ];
                        $connection->createCommand($sql, $params)->execute();
                        $noUrut++;
                    }
                }
            } else {
                $kodePenjualan = $id;
            }

            $tagihanObat = 0;
            foreach ($daftarPelayanan as $key => $unused) {
                $tagihanObat += $daftarTagihan[$key];
            }

            $tagihanRacik = 0;
            foreach ($daftarTagihanRacik as $tRacik) {
                $tagihanRacik += $tRacik;
            }

            $totalAwal = $tagihanObat + $tagihanRacik;
            $totalDiskon = $diskonObat + $diskonRacik;
            $pembulatan = ceil(($totalAwal + $jasaHargaObat22 + $hargaPembungkus - $totalDiskon) / 100) * 100;
            $totalJasaPelayanan = $pembulatan - $totalAwal - $hargaPembungkus + $totalDiskon;

            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                UPDATE db1.masterf_penjualandetail
                SET
                    total = :total,
                    jasapelayanan = :jasaPelayanan,
                    totalpembungkus = :totalPembungkus,
                    totaldiskon = :totalDiskon
                WHERE no_resep = :noResep
            ";
            $params = [
                ":total" => $pembulatan,
                ":jasaPelayanan" => $totalJasaPelayanan,
                ":totalPembungkus" => $hargaPembungkus,
                ":totalDiskon" => $totalDiskon,
                ":noResep" => $noResep,
            ];
            $connection->createCommand($sql, $params)->execute();

            if ($kodePenjualanSebelumnya) {
                $sql = /** @lang SQL */ "
                    -- FILE: ".__FILE__." 
                    -- LINE: ".__LINE__." 
                    UPDATE db1.masterf_antrian
                    SET kode_penjualan = :kodeBaru
                    WHERE kode_penjualan = :kodeLama
                ";
                $params = [":kodeBaru" => $kodePenjualan, ":kodeLama" => $kodePenjualanSebelumnya];
                $connection->createCommand($sql, $params)->execute();
            }
        } else {
            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT transfer
                FROM db1.masterf_penjualan
                WHERE
                    no_resep = :noResep
                    AND transfer != ''
                LIMIT 1
            ";
            $params = [":noResep" => $noResep];
            $transfer = $connection->createCommand($sql, $params)->queryScalar();
            $transfer ? $this->actionTransferUbahCaraBayar($noResep, $noPendaftaran) : null;

            $tagihan22 = 0;
            $returTagihan = 0;
            foreach ($daftarKodeObat1 as $key => $kodeObat) {
                $minJumlah = abs($daftarJumlah[$key]);
                $jumlah = $daftarJumlah[$key];
                $jumlah33 = $daftarJumlah[$key];

                $sql = /** @lang SQL */ "
                    -- FILE: ".__FILE__." 
                    -- LINE: ".__LINE__." 
                    SELECT TRUE
                    FROM db1.masterf_penjualan
                    WHERE
                        kodeObat = :kodeObat
                        AND jlhPenjualan < 0
                        AND no_resep = :noResep
                    LIMIT 1
                ";
                $params = [":kodeObat" => $kodeObat, ":noResep" => $noResep];
                $cekRetur = $connection->createCommand($sql, $params)->queryScalar();

                if ($jumlah >= 0 || $cekRetur) continue;

                $sql = /** @lang SQL */ "
                    -- FILE: ".__FILE__." 
                    -- LINE: ".__LINE__." 
                    DELETE FROM db1.masterf_penjualan
                    WHERE
                        kodeObat = :kodeObat
                        AND (jlhPenjualan = :jumlahPenjualan OR jlhPenjualan < 0)
                        AND no_resep = :noResep
                ";
                $params = [":kodeObat" => $kodeObat, ":jumlahPenjualan" => $jumlah, ":noResep" => $noResep];
                $connection->createCommand($sql, $params)->execute();

                $harga = $daftarHargaJual[$key];
                $jumlah = $minJumlah;

                $dataKatalog = $this->actionBridging5([
                    "fungsi" => "SELECT_KATALOG_BRG",
                    "kode" => $kodeObat
                ]);

                $dataKatalog[0]["KD_KATALOG"] ??= 0;

                $sql = /** @lang SQL */ "
                    -- FILE: ".__FILE__." 
                    -- LINE: ".__LINE__." 
                    SELECT
                        hja_setting AS hjaSetting,
                        hp_item     AS hpItem
                    FROM db1.masterf_katalog AS a
                    LEFT JOIN db1.relasif_hargaperolehan AS b ON b.id_katalog = a.kode
                    WHERE
                        a.kode = :kode
                        AND b.sts_hja != 0
                        AND b.sts_hjapb != 0
                    LIMIT 1
                ";
                $params = [":kode" => $kodeObat];
                $harga2 = $connection->createCommand($sql, $params)->queryOne();

                $noUrut = $noUrut ?: 0;
                $daftarTagihan[$key] = $harga2->hjaSetting * $jumlah;
                $tagihan22 += $harga2->hjaSetting * $jumlah;
                $returTagihan = $tagihan22;

                $daftarKodeObat2[$key] ? $daftarPelayanan[$key] = 0 : null;

                $sql = /** @lang SQL */ "
                    -- FILE: ".__FILE__." 
                    -- LINE: ".__LINE__." 
                    INSERT INTO db1.masterf_penjualan
                    SET
                        kode_rm = :kodeRekamMedis,
                        no_daftar = :noPendaftaran,
                        nama_pasien = :namaPasien,
                        id_racik = :idRacik,
                        signa1 = :signa1,
                        signa2 = :signa2,
                        signa3 = :signa3,
                        harga = :harga,
                        jlhPenjualan = :jumlahPenjualan,
                        signa = :signa,
                        kodePenjualan = :kodePenjualan,
                        kodeObat = :kodeObat,
                        tglPenjualan = :tanggalPenjualan,
                        kode_depo = :kodeDepo,
                        diskon = 0,
                        operator = :operator,
                        no_resep = :noResep
                ";
                $params = [
                    ":kodeRekamMedis" => $kodeRekamMedis,
                    ":noPendaftaran" => $noPendaftaran,
                    ":namaPasien" => $namaPasien,
                    ":idRacik" => $daftarIdRacik[$key],
                    ":signa1" => $daftarNamaSigna1[$key],
                    ":signa2" => $daftarNamaSigna2[$key],
                    ":signa3" => $daftarNamaSigna3[$key],
                    ":harga" => $harga,
                    ":jumlahPenjualan" => $jumlah33,
                    ":signa" => $daftarKodeSigna[$key],
                    ":kodePenjualan" => $editResep,
                    ":kodeObat" => $kodeObat,
                    ":tanggalPenjualan" => $todayValSystem,
                    ":kodeDepo" => Yii::$app->userFatma->kodeDepo,
                    ":operator" => $operator,
                    ":noResep" => $noResep,
                ];
                $connection->createCommand($sql, $params)->execute();

                $sql = /** @lang SQL */ "
                    -- FILE: ".__FILE__." 
                    -- LINE: ".__LINE__." 
                    SELECT kode_stokopname
                    FROM db1.relasif_ketersediaan AS c
                    INNER JOIN db1.transaksif_stokopname AS a ON a.kode = c.kode_stokopname
                    INNER JOIN db1.masterf_aktifasiso AS b ON b.kode = a.kode_reff
                    WHERE
                        c.id_depo = :idDepo
                        AND c.id_katalog = :idKatalog
                        AND b.status = 1
                    LIMIT 1
                ";
                $params = [":idDepo" => $idDepo, ":idKatalog" => $kodeObat];
                $kodeStokOpname = $connection->createCommand($sql, $params)->queryScalar();

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
                $params = [":idKatalog" => $kodeObat, ":idDepo" => $idDepo];
                $jumlahStokFisikBefore = (int) $connection->createCommand($sql, $params)->queryScalar();

                $sql = /** @lang SQL */ "
                    -- FILE: ".__FILE__." 
                    -- LINE: ".__LINE__." 
                    SELECT TRUE
                    FROM db1.masterf_penjualan
                    WHERE
                        no_resep = :noResep
                        AND kodeObat = :kodeObat
                        AND jlhPenjualan = :jumlahPenjualan
                    LIMIT 1
                ";
                $params = [":noResep" => $noResep, ":kodeObat" => $kodeObat, ":jumlahPenjualan" => -$jumlah33];
                $cekPenjualan = $connection->createCommand($sql, $params)->queryAll();

                if ($cekPenjualan && !$daftarIdRacik[$key]) {
                    $tagihan22 += 500;
                    $sql = /** @lang SQL */ "
                        -- FILE: ".__FILE__." 
                        -- LINE: ".__LINE__." 
                        UPDATE db1.masterf_penjualandetail
                        SET
                            jasapelayanan = jasapelayanan - 500,
                            total = total - 500
                        WHERE no_resep = :noResep
                    ";
                    $params = [":noResep" => $noResep];
                    $connection->createCommand($sql, $params)->execute();
                }
                $sql = /** @lang SQL */ "
                    -- FILE: ".__FILE__." 
                    -- LINE: ".__LINE__." 
                    INSERT INTO db1.relasif_ketersediaan
                    SET
                        id_depo = :idDepo,
                        kode_reff = :kodeRef,
                        kode_stokopname = :kodeStokopname,
                        kode_transaksi = 'R',
                        kode_store = '000000',
                        tipe_tersedia = 'penjualan',
                        tgl_tersedia = :tanggalTersedia,
                        no_batch = '-',
                        tgl_expired = :tanggalKadaluarsa,
                        id_katalog = :idKatalog,
                        harga_item = :hargaItem,
                        harga_perolehan = :hargaPerolehan,
                        jumlah_masuk = :jumlahMasuk,
                        jumlah_keluar = 0,
                        userid_last = :idUser,
                        jumlah_tersedia = :jumlahTersedia,
                        status = 1,
                        keterangan = :keterangan
                ";
                $params = [
                    ":idDepo" => $idDepo,
                    ":kodeRef" => $noResep,
                    ":kodeStokopname" => $kodeStokOpname,
                    ":tanggalTersedia" => $nowValSystem,
                    ":tanggalKadaluarsa" => $systemDate($todayValSystem, "+2 month"),
                    ":idKatalog" => $daftarKodeObat2[$key],
                    ":hargaItem" => $harga2->hjaSetting,
                    ":hargaPerolehan" => $harga2->hpItem,
                    ":jumlahMasuk" => $minJumlah,
                    ":idUser" => $idUser,
                    ":jumlahTersedia" => $jumlahStokFisikBefore + $minJumlah,
                    ":keterangan" => "Returan pasien $namaPasien",
                ];
                $connection->createCommand($sql, $params)->execute();

                $sql = /** @lang SQL */ "
                    -- FILE: ".__FILE__." 
                    -- LINE: ".__LINE__." 
                    UPDATE db1.transaksif_stokkatalog
                    SET
                        jumlah_stokfisik = jumlah_stokfisik + :minJumlah,
                        jumlah_stokadm = jumlah_stokadm + :minJumlah
                    WHERE
                        id_katalog = :idKatalog
                        AND id_depo = :idDepo
                ";
                $params = [":minJumlah" => $minJumlah, ":idKatalog" => $kodeObat, ":idDepo" => $idDepo];
                $connection->createCommand($sql, $params)->execute();
            }

            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT pembayaran
                FROM db1.masterf_penjualandetail
                WHERE no_resep = :noResep
                LIMIT 1
            ";
            $params = [":noResep" => $noResep];
            $pembayaran = $connection->createCommand($sql, $params)->queryScalar();

            if ($pembayaran != $pPembayaran) {
                if ($pPembayaran == "Tunai") {
                    $sql = /** @lang SQL */ "
                        -- FILE: ".__FILE__." 
                        -- LINE: ".__LINE__." 
                        UPDATE db1.masterf_penjualandetail
                        SET
                            pembayaran = :pembayaran,
                            kd_bayar = '001',
                            kd_jenis_carabayar = '01',
                            jns_carabayar = 'Tunai'
                        WHERE no_resep = :noResep
                    ";
                } else {
                    $sql = /** @lang SQL */ "
                        -- FILE: ".__FILE__." 
                        -- LINE: ".__LINE__." 
                        UPDATE db1.masterf_penjualandetail
                        SET pembayaran = :pembayaran
                        WHERE no_resep = :noResep
                    ";
                }
                $params = [":pembayaran" => $pPembayaran, ":noResep" => $noResep];
                $connection->createCommand($sql, $params)->execute();
            }
            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                UPDATE db1.masterf_penjualandetail
                SET total_retur = :totalRetur
                WHERE no_resep = :noResep
            ";
            $params = [":totalRetur" => $returTagihan, ":noResep" => $noResep];
            $connection->createCommand($sql, $params)->execute();
        }

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT jenisresep
            FROM db1.masterf_jenisresep
            WHERE kd_jenisresep = :kodeJenisResep
            LIMIT 1
        ";
        $params = [":kodeJenisResep" => $kodeJenisResep];
        $jenisResep = $connection->createCommand($sql, $params)->queryScalar();

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            UPDATE db1.masterf_penjualandetail
            SET
                jasapelayanan = :jasaPelayanan,
                jenisResep = :jenisResep
            WHERE no_resep = :noResep
        ";
        $params = [":jasaPelayanan" => $totalJasaPelayanan ?? 0, ":jenisResep" => $jenisResep, ":noResep" => $noResep];
        $connection->createCommand($sql, $params)->execute();

        return $noResep;
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/eresepdepodr.php#delete    the original method
     */
    public function actionDelete(): Response
    {
        $noResep = Yii::$app->request->post("noResep") ?? new MissingPostParamException("noResep");
        $connection = Yii::$app->dbFatma;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            DELETE FROM db1.masterf_penjualan
            WHERE no_resep = :noResep
        ";
        $params = [":noResep" => $noResep];
        $connection->createCommand($sql, $params)->execute();

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            DELETE FROM db1.masterf_penjualandetail
            WHERE no_resep = :noResep
        ";
        $params = [":noResep" => $noResep];
        $connection->createCommand($sql, $params)->execute();

        return new Response();
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/eresepdepodr.php#verifikasi    the original method
     */
    public function actionVerifikasi(): void
    {
        $noResep = Yii::$app->request->post("noResep") ?? throw new MissingPostParamException("noResep");
        $dateTime = Yii::$app->dateTime;
        $systemDate = $dateTime->transformFunc("systemDate");
        $todayValSystem = $dateTime->todayVal("system");
        $nowValSystem = $dateTime->nowVal("system");
        $namaUser = Yii::$app->userFatma->nama;
        $idDepo = Yii::$app->userFatma->idDepo;
        $connection = Yii::$app->dbFatma;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT -- all are in use, no view file.
                no_resep     AS noResep,
                nama_pasien  AS namaPasien,
                kodeObat     AS kodeObat,
                jlhPenjualan AS jumlahPenjualan
            FROM db1.masterf_penjualan
            WHERE no_resep = :noResep
        ";
        $params = [":noResep" => $noResep];
        $daftarPenjualan = $connection->createCommand($sql, $params)->queryAll();

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT verifikasi
            FROM db1.masterf_penjualan
            WHERE no_resep = :noResep
            LIMIT 1
        ";
        $params = [":noResep" => $noResep];
        $verifikasi = $connection->createCommand($sql, $params)->queryScalar();

        if ($verifikasi) return;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            UPDATE db1.masterf_penjualandetail
            SET iter2 = iter2 + 1
            WHERE no_resep = :noResep
        ";
        $params = [":noResep" => $noResep];
        $connection->createCommand($sql, $params)->execute();

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            UPDATE db1.masterf_penjualan
            SET
                verifikasi = :verifikasi,
                tglverifikasi = :tanggal
            WHERE no_resep = :noResep
        ";
        $params = [":verifikasi" => $namaUser, ":tanggal" => $nowValSystem, ":noResep" => $noResep];
        $connection->createCommand($sql, $params)->execute();

        foreach ($daftarPenjualan as $penjualan) {
            if (!$penjualan->kodeObat) continue;

            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT jumlah_tersedia
                FROM db1.relasif_ketersediaan
                WHERE
                    id_depo = :idDepo
                    AND id_katalog = :idKatalog
                ORDER BY id DESC
                LIMIT 1
            ";
            $params = [":idDepo" => $idDepo, ":idKatalog" => $penjualan->kodeObat];
            $jumlahTersediaBefore = (int) $connection->createCommand($sql, $params)->queryScalar();

            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT kode_stokopname
                FROM db1.relasif_ketersediaan AS c
                INNER JOIN db1.transaksif_stokopname AS a ON a.kode = c.kode_stokopname
                INNER JOIN db1.masterf_aktifasiso AS b ON b.kode = a.kode_reff
                WHERE
                    c.id_depo = :idDepo
                    AND c.id_katalog = :idKatalog
                    AND b.status = 1
                LIMIT 1
            ";
            $params = [":idDepo" => $idDepo, ":idKatalog" => $penjualan->kodeObat];
            $kodeStokOpname = $connection->createCommand($sql, $params)->queryScalar();

            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT -- all are in use, no view file.
                    b.hja_setting       AS hjaSetting,
                    b.hp_item           AS hpItem
                FROM db1.masterf_katalog AS a
                LEFT JOIN db1.relasif_hargaperolehan AS b ON b.id_katalog = a.kode
                WHERE
                    a.kode = :kode
                    AND (b.sts_hja != 0 OR b.sts_hjapb != 0)
                LIMIT 1
            ";
            $params = [":kode" => $val]; // TODO: php: uncategorized: trace $val comes from
            $harga2 = $connection->createCommand($sql, $params)->queryOne();

            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                INSERT INTO db1.relasif_ketersediaan
                SET
                    id_depo = :idDepo,
                    kode_reff = :kodeRef,
                    kode_stokopname = :kodeStokOpname,
                    kode_transaksi = 'R',
                    kode_store = '000000',
                    tipe_tersedia = 'penjualan',
                    tgl_tersedia = :tanggalTersedia,
                    no_batch = '-',
                    tgl_expired = :tanggalKadaluarsa,
                    id_katalog = :idKatalog,
                    harga_item = :hargaItem,
                    harga_perolehan = :hargaPerolehan,
                    jumlah_masuk = 0,
                    jumlah_keluar = :jumlahKeluar,
                    jumlah_tersedia = :jumlahTersedia,
                    status = 1,
                    keterangan = :keterangan
            ";
            $params = [
                ":idDepo" => $idDepo,
                ":kodeRef" => $penjualan->noResep,
                ":kodeStokOpname" => $kodeStokOpname,
                ":tanggalTersedia" => $nowValSystem,
                ":tanggalKadaluarsa" => $systemDate($todayValSystem, "+2 month"),
                ":idKatalog" => $penjualan->kodeObat,
                ":hargaItem" => $harga2->hjaSetting,
                ":hargaPerolehan" => $harga2->hpItem,
                ":jumlahKeluar" => $penjualan->jumlahPenjualan,
                ":jumlahTersedia" => $jumlahTersediaBefore - $penjualan->jumlahPenjualan,
                ":keterangan" => "Pemakaian pasien " . $penjualan->namaPasien,
            ];
            $connection->createCommand($sql, $params)->execute();

            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                UPDATE db1.masterf_ketersediaan
                SET jlhTersedia = jlhTersedia - :jumlahPenjualan
                WHERE
                    kodeDepo = :kodeDepo
                    AND kodeObat = :kodeObat
            ";
            $params = [":jumlahPenjualan" => $penjualan->jumlahPenjualan, ":kodeDepo" => $idDepo, ":kodeObat" => $penjualan->kodeObat];
            $connection->createCommand($sql, $params)->execute();

            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT sts_opname
                FROM db1.transaksif_stokopname
                WHERE id_depo = :idDepo
                LIMIT 1
            ";
            $params = [":idDepo" => $idDepo];
            $statusOpname = $connection->createCommand($sql, $params)->queryScalar();
            $minJumlahAdm = ($statusOpname == 1) ? 0 : $penjualan->jumlahPenjualan;

            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                UPDATE db1.transaksif_stokkatalog
                SET
                    jumlah_stokfisik = jumlah_stokfisik - :jumlahPenjualan,
                    jumlah_stokadm = jumlah_stokadm - :minJumlahAdm
                WHERE
                    id_depo = :idDepo
                    AND id_katalog = :idKatalog
            ";
            $params = [
                ":jumlahPenjualan" => $penjualan->jumlahPenjualan,
                ":minJumlahAdm" => $minJumlahAdm,
                ":idDepo" => $idDepo,
                ":idKatalog" => $penjualan->kodeObat
            ];
            $connection->createCommand($sql, $params)->execute();
        }

        $this->actionBridging4([
            "fungsi" => "tambah_TTGH_RESEP_REKAP3",
            "no_resep" => $noResep,
            "userverif" => $namaUser
        ]);
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/eresepdepodr.php#transferubahcarabayar    the original method
     */
    public function actionTransferUbahCaraBayar(): void
    {
        assert($_POST["id"] && $_POST["daftar"], new MissingPostParamException("id", "daftar"));
        ["id" => $noResep, "daftar" => $daftar] = Yii::$app->request->post();
        $nowValSystem = Yii::$app->dateTime->nowVal("system");
        $connection = Yii::$app->dbFatma;

        $getkeluar = $this->actionTestBridgeCekKeluar($daftar);
        if ($getkeluar[1] == 1) return;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT kode_rm
            FROM db1.masterf_penjualan
            WHERE no_resep = :noResep
            LIMIT 1
        ";
        $params = [":noResep" => $noResep];
        $kodeRekamMedis = $connection->createCommand($sql, $params)->queryScalar();

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            UPDATE db1.masterf_penjualan
            SET
                transfer = :transfer,
                tgltransfer = :tanggal
            WHERE no_resep = :noResep
        ";
        $params = [":transfer" => Yii::$app->userFatma->nama, ":tanggal" => $nowValSystem, ":noResep" => $noResep];
        $connection->createCommand($sql, $params)->execute();

        $this->actionBridging4(["fungsi" => "carinodaftar", "NORM" => $kodeRekamMedis]);
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/eresepdepodr.php#transfer    the original method
     */
    public function actionTransfer(): void
    {
        assert($_POST["id"] && $_POST["daftar"], new MissingPostParamException("id", "daftar"));
        ["id" => $noResep, "daftar" => $daftar] = Yii::$app->request->post();
        $nowValSystem = Yii::$app->dateTime->nowVal("system");
        $connection = Yii::$app->dbFatma;

        $getkeluar = $this->actionTestBridgeCekKeluar($daftar);
        if ($getkeluar[1] == 1) return;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT kode_rm
            FROM db1.masterf_penjualan
            WHERE no_resep = :noResep
            LIMIT 1
        ";
        $params = [":noResep" => $noResep];
        $kodeRekamMedis = $connection->createCommand($sql, $params)->queryScalar();

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            UPDATE db1.masterf_penjualan
            SET
                transfer = :transfer,
                tgltransfer = :tanggal
            WHERE no_resep = :noResep
        ";
        $params = [":transfer" =>Yii::$app->userFatma->nama, ":tanggal" => $nowValSystem, ":noResep" => $noResep];
        $connection->createCommand($sql, $params)->execute();

        $this->actionBridging4(["fungsi" => "carinodaftar", "NORM" => $kodeRekamMedis]);
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/eresepdepodr.php#listresep    the original method
     */
    public function actionTableResepData(): string
    {
        [   "dariTanggal" => $tanggalAwal,
            "sampaiTanggal" => $tanggalAkhir,
            "idDepo" => $idDepo,
            "kodeRekamMedis" => $kodeRekamMedis,
            "noResep" => $noResep,
        ] = Yii::$app->request->post();
        $toSystemDate = Yii::$app->dateTime->transformFunc("toSystemDate");
        $connection = Yii::$app->dbFatma;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT kode
            FROM db1.masterf_depo
            WHERE id = :id
            LIMIT 1
        ";
        $params = [":id" => $idDepo];
        $kodeDepo = $connection->createCommand($sql, $params)->queryScalar() ?? "";

        $kodeRekamMedis = $kodeRekamMedis ? str_pad($kodeRekamMedis, 8, "0", STR_PAD_LEFT) : $kodeRekamMedis;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT -- all are in use.
                a.no_resep      AS noResep,
                a.kode_rm       AS kodeRekamMedis,
                a.nama_pasien   AS namaPasien,
                a.verifikasi    AS verifikasi,
                a.tglverifikasi AS tanggalVerifikasi,
                a.transfer      AS transfer,
                c.jenisResep    AS jenisResep,
                d.no_antrian    AS noAntrian,
                c.totaldiskon   AS totalDiskon,
                c.tglResep1     AS tanggalResep,
                a.tglPenjualan  AS tanggalPenjualan,
                ''              AS totalCeil
            FROM db1.masterf_penjualan AS a
            LEFT JOIN db1.masterf_penjualandetail AS c ON c.no_resep = a.no_resep
            LEFT JOIN db1.masterf_antrian AS d ON d.kode_penjualan = a.kodePenjualan
            WHERE
                a.no_resep != ''
                AND (:kodeDepo = '' OR a.kode_depo = :kodeDepo)
                AND (:kodeRekamMedis = '' OR a.kode_rm = :kodeRekamMedis)
                AND (:noResep = '' OR a.no_resep = :noResep)  
                AND a.tglPenjualan >= :tanggalAwal
                AND a.tglPenjualan <= :tanggalAkhir
            GROUP BY a.no_resep
            ORDER BY a.tglPenjualan DESC
        ";
        $params = [
            ":kodeDepo" => $kodeDepo,
            ":kodeRekamMedis" => $kodeRekamMedis,
            ":noResep" => $noResep,
            ":tanggalAwal" => $toSystemDate($tanggalAwal) . " 00:00:00",
            ":tanggalAkhir" => $toSystemDate($tanggalAkhir) . " 23:59:59",
        ];
        $daftarResep = $connection->createCommand($sql, $params)->queryAll();

        foreach ($daftarResep as $resep) {
            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT no_resep
                FROM db1.masterf_penjualan
                WHERE
                    no_resep = :noResep
                    AND id_racik != ''
                    AND id_racik != 0
                GROUP BY id_racik
            ";
            $params = [":noResep" => $resep->noResep];
            $daftarRacik = $connection->createCommand($sql, $params)->queryAll();

            if (strtotime($resep->tanggalPenjualan) >= strtotime("2016-10-01")) {
                $totalRacikan = count($daftarRacik) * 1000;
                $pembelianBebas = 500;
            } else {
                $totalRacikan = count($daftarRacik) * 500;
                $pembelianBebas = 300;
            }

            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT
                    SUM(b.jasapelayanan) + :totalRacikan AS totalJasaPelayanan,
                    SUM(b.totalharga)                    AS totalJual
                FROM (
                    SELECT
                        CASE
                            WHEN (a.total <= 0 OR jenisResep = 'Pembelian Bebas') THEN 0
                            WHEN a.total > 0 AND (a.id_racik = ''  OR  a.id_racik = 0)  AND jenisResep != 'Pembelian Bebas' THEN :pembelianBebas
                            WHEN a.total > 0 AND (a.id_racik != '' AND a.id_racik != 0) AND jenisResep != 'Pembelian Bebas' THEN 0
                            ELSE 0
                        END AS jasapelayanan,
                        a.totalharga,
                        a.no_resep
                    FROM (
                        SELECT
                            SUM(jlhPenjualan)                   AS total,
                            id_racik, SUM(jlhPenjualan * harga) AS totalharga,
                            mp.no_resep                         AS no_resep,
                            jenisResep                          AS jenisResep
                        FROM db1.masterf_penjualan AS mp
                        INNER JOIN db1.masterf_penjualandetail AS mpd ON mpd.no_resep = mp.no_resep
                        WHERE mp.no_resep = :noResep
                        GROUP BY kodeObat
                        ORDER BY kodeObat ASC
                    ) AS a
                ) AS b
                GROUP BY b.no_resep
            ";
            $params = [":totalRacikan" => $totalRacikan, ":pembelianBebas" => $pembelianBebas, ":noResep" => $resep->noResep];
            $penjualan = $connection->createCommand($sql, $params)->queryOne();

            $resep->totalCeil = ceil(($penjualan->totalJual + $penjualan->totalJasaPelayanan - $resep->totalDiskon) / 100) * 100;
        }

        return json_encode(["daftarResep" => $daftarResep, "total" => count($daftarResep)]);
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/eresepdepodr.php#etiket    the original method
     */
    public function actionEtiket(): void
    {
        [   "noResep" => $noResep,
            "noPrinter" => $noPrinter,
            "arah" => $arah,
        ] = Yii::$app->request->post();
        $connection = Yii::$app->dbFatma;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT ip_address -- all are in use.
            FROM db1.printer_depo
            WHERE
                kode_depo = :kodeDepo
                AND no_printer = :noPrinter
                AND tipe_printer = 'zebra'
            LIMIT 1
        ";
        $params = [":kodeDepo" => Yii::$app->userFatma->idDepo, ":noPrinter" => $noPrinter];
        $ip = $connection->createCommand($sql, $params)->queryScalar() ?? "";

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                PJN.no_resep                  AS noResep,
                PJN.kodeObat                  AS kodeObat,
                KAT.nama_sediaan              AS namaSediaan,
                PJN.kode_rm                   AS kodeRekamMedis,
                TRIM(PJN.nama_pasien)         AS namaPasien,
                -- pasien_detail.tanggal_lahir tgl_lahir,
                PSM.tanggal_lahir             AS tanggalLahir,
                DAYOFMONTH(PSM.tanggal_lahir) AS tanggal,
                MONTH(PSM.tanggal_lahir)      AS bulan,
                YEAR(PSM.tanggal_lahir)       AS tahun,
                PJN.id_racik                  AS idRacik,
                PJN.signa1                    AS signa1,
                PJN.signa2                    AS signa2,
                PJN.signa3                    AS signa3,
                PJN.jlhPenjualan              AS kuantitas,
                PJD.keterangan                AS keterangan,
                ''                            AS namaSediaan1,
                ''                            AS namaSediaan2
            FROM db1.masterf_penjualan AS PJN
            INNER JOIN db1.masterf_katalog AS KAT ON PJN.kodeObat = KAT.kode
            -- LEFT JOIN db1.pasien_detail ON PJN.kode_rm = pasien_detail.no_rm
            LEFT JOIN db1.pasien_small AS PSM ON PJN.kode_rm = PSM.no_rm
            LEFT JOIN db1.masterf_penjualandetail AS PJD ON PJN.no_resep = PJD.no_resep
            WHERE
                PJN.no_resep = :noResep
                AND KAT.id_jenisbarang IN (1, 8, 9, 10, 11, 16, 17, 18, 21, 22, 23, 25, 26, 28)
                AND PJN.id_racik = ''
        UNION
            SELECT
                PJN.no_resep                     AS noResep,
                PJN.kodeObat                     AS kodeObat,
                -- KAT.nama_sediaan,
                CONCAT('Racikan ', PJN.id_racik) AS namaSediaan,
                PJN.kode_rm                      AS kodeRekamMedis,
                TRIM(PJN.nama_pasien)            AS namaPasien,
                -- pasien_detail.tanggal_lahir tgl_lahir,
                PSM.tanggal_lahir,
                DAYOFMONTH(PSM.tanggal_lahir)    AS tanggal,
                MONTH(PSM.tanggal_lahir)         AS bulan,
                YEAR(PSM.tanggal_lahir)          AS tahun,
                PJN.id_racik                     AS idRacik,
                PJN.signa1                       AS signa1,
                PJN.signa2                       AS signa2,
                PJN.signa3                       AS signa3,
                PJN.jlhPenjualan                 AS kuantitas,
                PJD.keterangan                   AS keterangan,
                ''                               AS namaSediaan1,
                ''                               AS namaSediaan2
            FROM db1.masterf_penjualan AS PJN
            INNER JOIN db1.masterf_katalog AS KAT ON PJN.kodeObat = KAT.kode
            -- LEFT JOIN db1.pasien_detail ON PJN.kode_rm = pasien_detail.no_rm
            LEFT JOIN db1.pasien_small AS PSM ON PJN.kode_rm = PSM.no_rm
            LEFT JOIN db1.masterf_penjualandetail AS PJD ON PJN.no_resep = PJD.no_resep
            WHERE
                PJN.no_resep = :noResep
                -- AND PJN.signa1 != ''
                AND KAT.id_jenisbarang IN (1, 8, 9, 10, 11, 16, 17, 18, 21, 22, 23, 25, 26, 28)
                AND PJN.id_racik != ''
            GROUP BY PJN.id_racik
        ";
        $params = [":noResep" => $noResep];
        $daftarResep = $connection->createCommand($sql, $params)->queryAll();

        foreach ($daftarResep as $key => $resep) {
            $namaSediaan = strtoupper($resep->namaSediaan);
            $offset = 25;
            $posTerakhir = 50;
            if ($offset >= strlen($namaSediaan)) {
                $resep->namaSediaan1 = strtoupper($namaSediaan);
                $resep->namaSediaan2 = "";
            } else {
                $breakIndex = strpos($namaSediaan, " ", $offset);
                $namaSediaan1 = substr($namaSediaan, 0, $breakIndex);
                if (strlen($namaSediaan1) > $offset) {
                    $namaSediaan1 = substr($namaSediaan1, 0, $offset);
                }
                $resep->namaSediaan1 = strtoupper($namaSediaan1);
                $resep->namaSediaan2 = strtoupper(substr($namaSediaan, $breakIndex, $posTerakhir));
            }
        }

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => "http://$ip/printer.php",
            CURLOPT_USERAGENT => "Codular Sample cURL Request",
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => [
                "resep" => json_encode($daftarResep),
                "direction" => $arah,
            ]
        ]);
        curl_close($curl);
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/eresepdepodr.php#cetakstruk    the original method
     */
    public function actionViewStrukData(): string
    {
        $noResep = Yii::$app->request->post("noResep") ?? new MissingPostParamException("noResep");
        $connection = Yii::$app->dbFatma;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT -- all are in use.
                a.nama_pasien     AS nama,
                b.jenis_kelamin   AS kelamin,
                b.alamat_jalan    AS alamat,
                e.nm_kamar        AS namaKamar,
                e.pembayaran      AS caraBayar,
                e.tglResep1       AS dariTanggal,
                e.tglResep2       AS sampaiTanggal,
                b.no_rm           AS kodeRekamMedis,
                e.iter            AS iter,
                e.totalpembungkus AS totalPembungkus,
                e.keterangan      AS keterangan,
                a.transfer        AS transfer,
                e.jenisResep      AS jenisResep,
                a.verifikasi      AS verifikasi,
                e.KD_INST         AS kodeInstalasi,
                a.tglPenjualan    AS tanggalPenjualan,
                a.no_resep        AS noResep,
                a.no_daftar       AS noPendaftaran
            FROM db1.masterf_penjualan AS a
            LEFT JOIN db1.pasien_small AS b ON b.no_rm = a.kode_rm
            LEFT JOIN db1.masterf_penjualandetail AS e ON e.no_resep = a.no_resep
            LEFT JOIN db1.masterf_antrian AS f ON f.kode_penjualan = a.kodePenjualan
            WHERE a.no_resep = :noResep
            LIMIT 1
        ";
        $params = [":noResep" => $noResep];
        $pasien = $connection->createCommand($sql, $params)->queryOne();

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT -- all are in use.
                a.kode_racik        AS kodeRacik,
                a.nama_racik        AS namaRacik,
                a.no_racik          AS noRacik,
                b.nama_barang       AS namaBarang,
                a.ketjumlah         AS keteranganJumlah,
                SUM(a.jlhPenjualan) AS jumlahPenjualan,
                a.harga             AS hargaJual,
                ''                  AS urutanObat,
                ''                  AS urutanRacik
            FROM db1.masterf_penjualan AS a
            INNER JOIN db1.masterf_katalog AS b ON b.kode = a.kodeObat
            LEFT JOIN db1.masterf_kemasan AS c ON c.id = b.id_kemasankecil
            LEFT JOIN db1.master_signa AS d ON d.kode = a.signa
            LEFT JOIN db1.relasif_hargaperolehan AS bb ON bb.id_katalog = b.kode
            WHERE
                a.no_resep = :noResep
                AND bb.sts_hja != 0
                AND bb.sts_hjapb != 0
            GROUP BY b.kode
            ORDER BY a.kode ASC
        ";
        $params = [":noResep" => $noResep];
        $daftarObat = $connection->createCommand($sql, $params)->queryAll();

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT NM_INST -- all are in use.
            FROM db1.masterf_kode_inst
            WHERE kd_inst = :kodeInstalasi
            LIMIT 1
        ";
        $params = [":kodeInstalasi" => $pasien->kodeInstalasi];
        $namaInstalasi = $connection->createCommand($sql, $params)->queryScalar();

        $kodeRacik = "";
        $urutanRacik = 1;
        $urutanObat = 1;

        $daftarObat2 = [];

        foreach ($daftarObat as $obat) {
            if ($obat->kodeRacik) {
                if ($kodeRacik != $obat->kodeRacik) {
                    $urutanRacik = 1;
                    $daftarObat2[] = [
                        "urutanRacik" => "",
                        "urutanObat" => $urutanObat,
                        "namaBarang" => $obat->namaRacik,
                        "noRacik" => $obat->noRacik
                    ];
                    $kodeRacik = $obat->kodeRacik;
                    $urutanObat++;
                }
                $obat->urutanRacik = $urutanRacik;
                $obat->urutanObat = "";
                $urutanRacik++;
            } else {
                $obat->urutanRacik = "";
                $obat->urutanObat = $urutanObat;
                $urutanObat++;
            }

            $daftarObat2[] = $obat;
        }

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT verifikasi -- all are in use.
            FROM db1.masterf_penjualan
            WHERE
                no_resep = :noResep
                AND verifikasi != ''
            LIMIT 1
        ";
        $params = [":noResep" => $pasien->noResep];
        $verifikasi = $connection->createCommand($sql, $params)->queryScalar();

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT transfer -- all are in use.
            FROM db1.masterf_penjualan
            WHERE
                no_resep = :noResep
                AND transfer != ''
            LIMIT 1
        ";
        $params = [":noResep" => $pasien->noResep];
        $transfer = $connection->createCommand($sql, $params)->queryScalar();

        $pasien->verifikasi = $verifikasi ?: $pasien->verifikasi;
        $pasien->transfer = $transfer ?: $pasien->transfer;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT no_resep -- all are in use.
            FROM db1.masterf_penjualan
            WHERE
                no_resep = :noResep
                AND id_racik != ''
                AND id_racik != 0
            GROUP BY id_racik
        ";
        $params = [":noResep" => $pasien->noResep];
        $daftarRacik = $connection->createCommand($sql, $params)->queryAll();

        if (strtotime($pasien->tanggalPenjualan) >= strtotime("2016-10-01")) {
            $totalRacikan = count($daftarRacik) * 1000;
            $pembelianBebas = 500;
        } else {
            $totalRacikan = count($daftarRacik) * 500;
            $pembelianBebas = 300;
        }

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT -- all are in use.
                SUM(b.jasapelayanan) + :totalRacikan AS totalJasaPelayanan,
                SUM(b.totalharga)                    AS totalJual,
                FLOOR(b.totaldiskon)                 AS totalDiskon
            FROM (
                SELECT
                    CASE
                        WHEN (a.total <= 0 OR jenisResep = 'Pembelian Bebas') THEN 0
                        WHEN a.total > 0 AND (a.id_racik = ''  OR  a.id_racik = 0)  AND jenisResep != 'Pembelian Bebas' THEN :pembelianBebas
                        WHEN a.total > 0 AND (a.id_racik != '' AND a.id_racik != 0) AND jenisResep != 'Pembelian Bebas' THEN 0
                        ELSE 0
                    END AS jasapelayanan,
                    a.totalharga,
                    a.no_resep,
                    a.totaldiskon
                FROM (
                    SELECT
                        SUM(jlhPenjualan)         AS total,
                        id_racik                  AS id_racik,
                        SUM(jlhPenjualan * harga) AS totalharga,
                        mp.no_resep               AS no_resep,
                        jenisResep                AS jenisResep,
                        mpd.totaldiskon           AS totaldiskon
                    FROM db1.masterf_penjualan AS mp
                    INNER JOIN db1.masterf_penjualandetail AS mpd ON mpd.no_resep = mp.no_resep
                    WHERE mp.no_resep = :noResep
                    GROUP BY kodeObat
                    ORDER BY kodeObat ASC
                ) AS a
            ) AS b
            GROUP BY b.no_resep
        ";
        $params = [":totalRacikan" => $totalRacikan, ":pembelianBebas" => $pembelianBebas, ":noResep" => $pasien->noResep];
        $penjualan = $connection->createCommand($sql, $params)->queryOne();

        $totalCeil = ceil(($penjualan->totalJual - $penjualan->totalDiskon) / 100) * 100;
        $penjualan->totalJasaPelayanan += $totalCeil - ($penjualan->totalJual - $penjualan->totalDiskon);
        $grandTotal = $penjualan->totalJual - $penjualan->totalDiskon + $pasien->totalPembungkus + $penjualan->totalJasaPelayanan;

        return json_encode([
            "daftarObat" => $daftarObat2, // truely correct $daftarObat2 (not $daftarObat)
            "pasien" => $pasien,
            "namaDepo" => Yii::$app->userFatma->namaDepo,
            "namaInstalasi" => $namaInstalasi,
            "operator" =>Yii::$app->userFatma->username,
            "penjualan" => $penjualan,
            "grandTotal" => $grandTotal,
        ]);
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/eresepdepodr.php#cetakresepdr    the original method
     */
    public function actionCetakResepDr(): string
    {
        $noResep = Yii::$app->request->post("noResep") ?? throw new MissingPostParamException("noResep");
        $connection = Yii::$app->dbFatma;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                a.nama_pasien AS namaPasien,
                e.pembayaran  AS pembayaran,
                e.dokter      AS dokter,
                e.tglResep1   AS tanggalResep1,
                e.tglResep2   AS tanggalResep2,
                a.kode_rm     AS kodeRekamMedis,
                a.no_daftar   AS noPendaftaran,
                a.no_resep    AS noResep
            FROM db1.masterf_penjualan AS a
            LEFT JOIN db1.masterf_penjualandetail AS e ON e.no_resep = a.no_resep
            WHERE a.no_resep = :noResep
            LIMIT 1
        ";
        $params = [":noResep" => $noResep];
        $pasien = $connection->createCommand($sql, $params)->queryOne();

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT -- all are in use, no view file.
                SUM(a.jlhPenjualan) AS subtotalJumlah,
                a.id_racik          AS idRacik,
                a.keterangan_obat   AS keteranganObat,
                a.signa1            AS signa1,
                a.signa2            AS signa2,
                a.signa3            AS signa3,
                b.nama_barang       AS namaBarang
            FROM db1.masterf_penjualan AS a
            LEFT JOIN db1.masterf_katalog AS b ON b.kode = a.kodeObatdr
            WHERE a.no_resep = :noResep
            GROUP BY a.kodeObat
            ORDER BY a.id_racik ASC
        ";
        $params = [":noResep" => $noResep];
        $daftarObat = $connection->createCommand($sql, $params)->queryAll();

        $daftarHalaman = [];

        $h = 0; // index halaman
        $b = 0; // index baris
        $barisPerHalaman = 18;

        foreach ($daftarObat as $obat) {
            $daftarHalaman[$h][$b] = [
                "nama_barang" => $obat->namaBarang,
                "jumlah" => $obat->subtotalJumlah,
                "keterangan_obat" => $obat->keteranganObat,
                "signa1" => $obat->signa1,
                "signa2" => $obat->signa2,
                "signa3" => $obat->signa3,
                "id_racik" => $obat->idRacik,
            ];

            if ($b > $barisPerHalaman) {
                $h++;
                $b = 0;
            } else {
                $b++;
            }
        }

        $view = new CetakResepDr(daftarHalaman: $daftarHalaman, pasien: $pasien);
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/eresepdepodr.php#cetakresep    the original method
     */
    public function actionCetakResep(): string
    {
        $noResep = Yii::$app->request->post("noResep") ?? throw new MissingPostParamException("noResep");
        $connection = Yii::$app->dbFatma;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                e.pembayaran    AS pembayaran,
                a.kode_rm       AS kodeRekamMedis,
                a.nama_pasien   AS namaPasien,
                e.tglResep1     AS tanggalResep1,
                a.tglverifikasi AS tanggalVerifikasi,
                a.verifikasi    AS verifikasi,
                a.no_resep      AS noResep,           -- in use
                a.no_daftar     AS noPendaftaran      -- in use
            FROM db1.masterf_penjualan AS a
            LEFT JOIN db1.masterf_penjualandetail AS e ON e.no_resep = a.no_resep
            WHERE a.no_resep = :noResep
            LIMIT 1
        ";
        $params = [":noResep" => $noResep];
        $pasien = $connection->createCommand($sql, $params)->queryOne();

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT -- all are in use, no view file.
                SUM(a.jlhPenjualan) AS subtotalJumlah,
                a.id_racik          AS idRacik,
                a.keterangan_obat   AS keteranganObat,
                a.signa1            AS signa1,
                a.signa2            AS signa2,
                a.signa3            AS signa3,
                b.nama_barang       AS namaBarang
            FROM db1.masterf_penjualan AS a
            LEFT JOIN db1.masterf_katalog AS b ON b.kode = a.kodeObatdr
            WHERE a.no_resep = :noResep
            GROUP BY a.kodeObat
            ORDER BY a.id_racik ASC
        ";
        $params = [":noResep" => $noResep];
        $daftarObat = $connection->createCommand($sql, $params)->queryAll();

        /* note: this query is truely use "sirs" db (defined by load database statement) */
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                a.tgllahir      AS tanggalLahir,
                a.kelamin       AS kelamin,
                a.nopendaftaran AS noPendaftaran,
                b.rwyt_alergi   AS riwayatAlergi,
                c.tng_badan     AS tinggiBadan,
                c.brt_badan     AS beratBadan,
                a.kdpoli        AS kodePoli,
                d.nm_poli       AS namaPoli,
                a.kdpelaksana   AS kodePelaksana,
                e.nm_penerima   AS namaPenerima,
                f.no_sip        AS noSip,
                a.tgldaftar     AS tanggalPendaftaran
            FROM sirs.tdft_pendaftaran AS a
            LEFT JOIN sirs.tdet_kaj_dokter AS b on b.no_rm = a.norm
            LEFT JOIN sirs.tdet_kaj_perawat AS c on c.no_rm = a.norm
            LEFT JOIN sirs.mdft_poli_smf AS d on d.kd_poli = a.kdpoli
            LEFT JOIN sirs.mmas_penerima AS e on e.kd_penerima = a.kdpelaksana
            LEFT JOIN sirs.mmas_sip AS f on f.kd_dokter = a.kdpelaksana
            WHERE
                a.nopendaftaran = :noPendaftaran
                AND d.kd_inst = 40
                AND f.tgl_awal < a.tgldaftar
                AND f.tgl_akhir > a.tgldaftar
        ";
        $params = [":noPendaftaran" => $pasien->noPendaftaran];
        $resep = $connection->createCommand($sql, $params)->queryOne();

        $daftarHalaman = [];

        $h = 0; // index halaman
        $b = 0; // index baris
        $barisPerHalaman = 5;

        foreach ($daftarObat as $obat) {
            $daftarHalaman[$h][$b] = [
                "nama_barang" => $obat->namaBarang,
                "jumlah" => $obat->subtotalJumlah,
                "keterangan_obat" => $obat->keteranganObat,
                "signa1" => $obat->signa1,
                "signa2" => $obat->signa2,
                "signa3" => $obat->signa3,
                "id_racik" => $obat->idRacik
            ];

            if ($b > $barisPerHalaman) {
                $h++;
                $b = 0;
            } else {
                $b++;
            }
        }

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT no_resep
            FROM db1.masterf_penjualan
            WHERE
                no_resep = :noResep
                AND id_racik != ''
                AND id_racik != 0
            GROUP BY id_racik
        ";
        $params = [":noResep" => $pasien->noResep];
        $daftarRacik = $connection->createCommand($sql, $params)->queryAll();

        $totalRacikan = count($daftarRacik) * 500;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                SUM(b.jasapelayanan) + :totalRacikan AS totalJasaPelayanan,
                SUM(b.totalharga)                    AS totalJual,
                FLOOR(b.totaldiskon)                 AS totalDiskon
            FROM (
                SELECT
                    CASE
                        WHEN (a.total <= 0 OR jenisResep = 'Pembelian Bebas') THEN 0
                        WHEN a.total > 0 AND (a.id_racik = ''  OR  a.id_racik = 0)  AND jenisResep != 'Pembelian Bebas' THEN 300
                        WHEN a.total > 0 AND (a.id_racik != '' AND a.id_racik != 0) AND jenisResep != 'Pembelian Bebas' THEN 0
                        ELSE 0
                    END AS jasapelayanan,
                    a.totalharga,
                    a.no_resep,
                    a.totaldiskon
                FROM (
                    SELECT
                        SUM(jlhPenjualan)         AS total,
                        id_racik                  AS id_racik,
                        SUM(jlhPenjualan * harga) AS totalharga,
                        mp.no_resep               AS no_resep,
                        jenisResep                AS jenisResep,
                        mpd.totaldiskon           AS totaldiskon
                    FROM db1.masterf_penjualan AS mp
                    INNER JOIN db1.masterf_penjualandetail AS mpd ON mpd.no_resep = mp.no_resep
                    WHERE mp.no_resep = :noResep
                    GROUP BY kodeObat
                    ORDER BY kodeObat ASC
                ) AS a
            ) AS b
            GROUP BY b.no_resep
        ";
        $params = [":totalRacikan" => $totalRacikan, ":noResep" => $pasien->noResep];
        $penjualan = $connection->createCommand($sql, $params)->queryOne();

        $totalCeil = ceil(($penjualan->totalJual - $penjualan->totalDiskon) / 100) * 100;
        $penjualan->totalJasaPelayanan += $totalCeil - ($penjualan->totalJual - $penjualan->totalDiskon);

        $view = new CetakResep(daftarHalaman: $daftarHalaman, pasien: $pasien, resep: $resep);
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/eresepdepodr.php#cetakstruk3    the original method
     */
    public function actionCetakStruk3(): string
    {
        $noResep = Yii::$app->request->post("noResep") ?? throw new MissingPostParamException("noResep");
        $connection = Yii::$app->dbFatma;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                a.nama_pasien     AS namaPasien,
                b.jenis_kelamin   AS kelamin,
                b.alamat_jalan    AS alamatJalan,
                e.nm_kamar        AS namaKamar,
                e.pembayaran      AS pembayaran,
                e.tglResep1       AS tanggalResep1,
                e.tglResep2       AS tanggalResep2,
                b.no_rm           AS kodeRekamMedis,
                a.no_daftar       AS noPendaftaran,
                e.iter            AS iter,
                e.totaldiskon     AS totalDiskon,
                e.totalpembungkus AS totalPembungkus,   -- in use
                a.verifikasi      AS verifikasi,
                a.tglverifikasi   AS tanggalVerifikasi,
                e.KD_INST         AS kodeInstalasi,     -- in use
                a.no_resep        AS noResep,           -- in use
                a.tglPenjualan    AS tanggalPenjualan   -- in use
            FROM db1.masterf_penjualan AS a
            LEFT JOIN db1.pasien_small AS b ON b.no_rm = a.kode_rm
            LEFT JOIN db1.masterf_penjualandetail AS e ON e.no_resep = a.no_resep
            LEFT JOIN db1.masterf_antrian AS f ON f.kode_penjualan = a.kodePenjualan
            WHERE a.no_resep = :noResep
            LIMIT 1
        ";
        $params = [":noResep" => $noResep];
        $pasien = $connection->createCommand($sql, $params)->queryOne();

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                a.kode                   AS kode,
                a.no_resep               AS noResep,
                a.no_penjualan           AS noPenjualan,
                a.diskon                 AS diskon,
                a.jasa                   AS jasa,
                a.kodePenjualan          AS kodePenjualan,
                a.kode_rm                AS kodeRekamMedis,
                a.no_daftar              AS noPendaftaran,
                a.nama_pasien            AS namaPasien,
                a.kodeObat               AS kodeObat,
                a.kodeObatdr             AS kodeObatDokter,
                a.nama_obatdr            AS namaObatDokter,
                a.urutan                 AS urutan,
                SUM(a.jlhPenjualan)      AS subtotalJumlah,
                a.jlhPenjualandr         AS jumlahPenjualanDokter,
                a.signa                  AS signa,
                a.hna                    AS hna,
                a.hp                     AS hp,
                a.harga                  AS harga,
                a.id_racik               AS idRacik,
                a.kode_racik             AS kodeRacik,
                a.nama_racik             AS namaRacik,
                a.no_racik               AS noRacik,
                a.ketjumlah              AS keteranganJumlah,
                a.keterangan_obat        AS keteranganObat,
                a.kode_depo              AS kodeDepo,
                a.ranap                  AS rawatInap,
                a.tglPenjualan           AS tanggalPenjualan,
                a.lunas                  AS lunas,
                a.verifikasi             AS verifikasi,
                a.transfer               AS transfer,
                a.resep                  AS resep,
                a.tglverifikasi          AS tanggalVerifikasi,
                a.tgltransfer            AS tanggalTransfer,
                a.operator               AS operator,
                a.tglbuat                AS tanggalPembuatan,
                a.signa1                 AS signa1,
                a.signa2                 AS signa2,
                a.signa3                 AS signa3,
                a.dokter_perobat         AS dokterPerObat,
                a.bayar                  AS bayar,
                a.tglbayar               AS tanggalPembayaran,
                a.checking_ketersediaan  AS cekKetersediaan,
                a.keteranganobat         AS keteranganObat,       -- duplicate upward
                a.kode_drperobat         AS kodeDokterPerObat,
                a.kode_operator          AS kodeOperator,
                a.kode_verifikasi        AS kodeVerifikasi,
                a.kode_transfer          AS kodeTransfer,
                d.nama                   AS namaSigna,
                z.hja_setting            AS hjaSetting,         -- in use
                a.jlhPenjualan           AS jumlahPenjualan     -- in use
            FROM db1.masterf_penjualan AS a
            INNER JOIN db1.masterf_katalog AS b ON b.kode = a.kodeObat
            LEFT JOIN db1.masterf_kemasan AS c ON c.id = b.id_kemasankecil
            LEFT JOIN db1.master_signa AS d ON d.kode = a.signa
            INNER JOIN db1.relasif_hargaperolehan AS z ON z.id_katalog = b.kode
            WHERE
                a.no_resep = :noResep
                AND z.sts_hja = 1
            GROUP BY a.kodeObat
            ORDER BY a.id_racik ASC
        ";
        $params = [":noResep" => $noResep];
        $daftarObat = $connection->createCommand($sql, $params)->queryAll();

        $daftarHalaman = [];

        $h = 0; // index halaman
        $b = 0; // index baris
        $barisPerHalaman = 18;

        foreach ($daftarObat as $i => $obat) {
            $daftarHalaman[$h][$b] = [
                "i" => $i,
                "subtotal_harga" => ceil($obat->hjaSetting * $obat->jumlahPenjualan),
            ];

            if ($b > $barisPerHalaman) {
                $h++;
                $b = 0;
            } else {
                $b++;
            }
        }

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT NM_INST
            FROM db1.masterf_kode_inst
            WHERE kd_inst = :kodeInstalasi
            LIMIT 1
        ";
        $params = [":kodeInstalasi" => $pasien->kodeInstalasi];
        $namaInstalasi = (string) $connection->createCommand($sql, $params)->queryScalar();

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT no_resep
            FROM db1.masterf_penjualan
            WHERE
                no_resep = :noResep
                AND id_racik != ''
                AND id_racik != 0
            GROUP BY id_racik
        ";
        $params = [":noResep" => $pasien->noResep];
        $daftarRacik = $connection->createCommand($sql, $params)->queryAll();

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                SUM(b.jasapelayanan) + :totalRacikan AS totalJasaPelayanan,
                SUM(b.totalharga)                    AS totalJual,
                FLOOR(b.totaldiskon)                 AS totalDiskon
            FROM (
                SELECT
                    CASE
                        WHEN (a.total <= 0 OR jenisResep = 'Pembelian Bebas') THEN 0
                        WHEN a.total > 0 AND (a.id_racik = ''  OR  a.id_racik = 0)  AND jenisResep != 'Pembelian Bebas' THEN :pembelianBebas
                        WHEN a.total > 0 AND (a.id_racik != '' AND a.id_racik != 0) AND jenisResep != 'Pembelian Bebas' THEN 0
                        ELSE 0
                    END AS jasapelayanan,
                    a.totalharga,
                    a.no_resep,
                    a.totaldiskon
                FROM (
                    SELECT
                        SUM(jlhPenjualan)         AS total,
                        id_racik                  AS id_racik,
                        SUM(jlhPenjualan * harga) AS totalharga,
                        mp.no_resep               AS no_resep,
                        jenisResep                AS jenisResep,
                        mpd.totaldiskon           AS totaldiskon
                    FROM db1.masterf_penjualan AS mp
                    INNER JOIN db1.masterf_penjualandetail AS mpd ON mpd.no_resep = mp.no_resep
                    WHERE mp.no_resep = :noResep
                    GROUP BY kodeObat
                    ORDER BY kodeObat ASC
                ) AS a
            ) AS b
            GROUP BY b.no_resep
        ";

        $daftarLainnya = [];

        foreach ($daftarHalaman as $key => $halaman) {
            unset($halaman);
            if (strtotime($pasien->tanggalPenjualan) >= strtotime("2016-10-01")) {
                $totalRacikan = count($daftarRacik) * 1000;
                $pembelianBebas = 500;
            } else {
                $totalRacikan = count($daftarRacik) * 500;
                $pembelianBebas = 300;
            }

            $params = [":totalRacikan" => $totalRacikan, ":pembelianBebas" => $pembelianBebas, ":noResep" => $pasien->noResep];
            $penjualan = $connection->createCommand($sql, $params)->queryOne();

            $totalCeil = ceil(($penjualan->totalJual - $penjualan->totalDiskon) / 100) * 100;
            $penjualan->totalJasaPelayanan += $totalCeil - ($penjualan->totalJual - $penjualan->totalDiskon);
            $grandTotal = $penjualan->totalJual - $penjualan->totalDiskon + $pasien->totalPembungkus + $penjualan->totalJasaPelayanan;

            $daftarLainnya[$key] = [
                "totalJual" => $penjualan->totalJual,
                "totalJp" => $penjualan->totalJasaPelayanan,
                "grandTotal" => $grandTotal,
            ];
        }

        $view = new CetakStrukNew(
            daftarHalaman: $daftarHalaman,
            pasien:        $pasien,
            namaInstalasi: $namaInstalasi,
            namaDepo:      Yii::$app->userFatma->namaDepo,
            daftarObat:    $daftarObat,
            jumlahHalaman: count($daftarHalaman),
            daftarLainnya: $daftarLainnya,
        );
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/eresepdepodr.php#antrian2    the original method
     */
    public function actionAntrian2(): string
    {
        $kodePenjualan = Yii::$app->request->post("kodePenjualan") ?? throw new MissingPostParamException("kodePenjualan");
        $todayValUser = Yii::$app->dateTime->todayVal("user");
        $connection = Yii::$app->dbFatma;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT no_antrian
            FROM db1.masterf_antrian
            WHERE
                tanggal = :tanggal
                AND kode_penjualan = :kodePenjualan
            LIMIT 1
        ";
        $params = [":tanggal" => $todayValUser, ":kodePenjualan" => $kodePenjualan];
        $noAntrian = $connection->createCommand($sql, $params)->queryScalar();

        if (!$noAntrian) {
            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                SELECT no_antrian
                FROM db1.masterf_antrian
                WHERE tanggal = :tanggal
                ORDER BY no_antrian DESC
                LIMIT 1
            ";
            $params = [":tanggal" => $todayValUser];
            $noAntrian = $connection->createCommand($sql, $params)->queryScalar() + 1;

            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                INSERT INTO db1.masterf_antrian
                SET
                    no_antrian = :noAntrian,
                    kode_penjualan = :kodePenjualan,
                    tanggal = :tanggal
            ";
            $params = [":noAntrian" => $noAntrian, ":kodePenjualan" => $kodePenjualan, ":tanggal" => $todayValUser];
            $connection->createCommand($sql, $params)->execute();
        }

        $view = new Antrian2(kodePenjualan: $kodePenjualan, noAntrian: $noAntrian);
        return $view->__toString();
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/eresepdepodr.php#testbridge_cekkeluar    the original method
     * @see http://127.0.0.1/bridging/Bridging.php#getDataPasienKeluar    the original method
     */
    private function actionTestBridgeCekKeluar(string $id): string
    {
        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT TDFT_KELUAR.* 
            FROM db_his_live.TDFT_PENDAFTARAN, TDFT_KELUAR
            WHERE
                TDFT_PENDAFTARAN.NO_PENDAFTARAN = TDFT_KELUAR.NO_PENDAFTARAN
                AND TDFT_KELUAR.NO_PENDAFTARAN = :noPendaftaran
                AND TDFT_KELUAR.KD_CARAKELUAR != '05'
                AND TDFT_PENDAFTARAN.KD_INST IN ('02', '04', '05', '06', '07', 19)
        ";
        $params = [":noPendaftaran" => $id];
        $result = $connection->createCommand($sql, $params)->queryAll();
        return json_encode($result);
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/eresepdepodr.php#bridging4    the original method
     */
    private function actionBridging4(array $data): string
    {
        $url = "http://202.137.25.13/bridging/Bridging_latihan.php";
        $dataString = "";
        foreach ($data as $key => $value) {
            $dataString .= $key . '=' . $value . '&';
        }

        $post = curl_init();
        curl_setopt($post, CURLOPT_URL, $url);
        curl_setopt($post, CURLOPT_POST, count($data));
        curl_setopt($post, CURLOPT_POSTFIELDS, $dataString);
        curl_setopt($post, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($post, CURLOPT_HEADER, 0);
        $result = curl_exec($post);
        curl_close($post);

        return $result;
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/eresepdepodr.php#getCounter2 the original method
     */
    private function getCounter2(string $id): int
    {
        $connection = Yii::$app->dbFatma;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT COUNT(*)
            FROM db1.counterid
            WHERE counter LIKE :counter
        ";
        $params = [":counter" => "$id%"];
        return (int) $connection->createCommand($sql, $params)->queryScalar() ?: 0;
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/eresepdepodr.php#addCounter2 the original method
     */
    private function addCounter2(string $id): string
    {
        $connection = Yii::$app->dbFatma;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            INSERT IGNORE INTO db1.counterid
            SET counter = :counter
        ";
        $params = [":counter" => $id];
        $berhasilTambah = $connection->createCommand($sql, $params)->execute();

        return $berhasilTambah ? "OK" : "NO";
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/eresepdepodr.php#bridging5    the original method
     */
    private function actionBridging5(array $data): array
    {
        $url = "http://202.137.25.13/bridging/Bridging_latihan.php";
        $dataString = "";
        foreach ($data as $key => $value) {
            $dataString .= $key . '=' . $value . '&';
        }

        $post = curl_init();
        curl_setopt($post, CURLOPT_URL, $url);
        curl_setopt($post, CURLOPT_POST, count($data));
        curl_setopt($post, CURLOPT_POSTFIELDS, $dataString);
        curl_setopt($post, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($post, CURLOPT_HEADER, 0);
        $result = curl_exec($post);
        curl_close($post);

        return json_decode($result);
    }
}
