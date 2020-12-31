<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\controllers;

use tlm\his\FatmaPharmacy\models\PasienModel;
use tlm\libs\LowEnd\components\DateTimeException;
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
class PasienController extends BaseController
{
    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/master/pasien.php#add    the original method
     */
    public function actionAdd(): void
    {
        $toSystemDate = Yii::$app->dateTime->transformFunc("toSystemDate");

        [   "kodeRekamMedis" => $kodeRekamMedis,
            "nama" => $nama,
            "tempatLahir" => $tempatLahir,
            "tanggalLahir" => $tanggalLahir,
            "kelamin" => $kelamin,
            "golonganDarah" => $golonganDarah,
            "alamatJalan" => $alamatJalan,
            "rt" => $rt,
            "rw" => $rw,
            "kodePos" => $kodePos,
            "noTelefon" => $noTelefon,
            "noHp" => $noHp,
            "propinsi" => $propinsi,
            "kota" => $kota,
            "kecamatan" => $kecamatan,
            "kelurahan" => $kelurahan,
            "pekerjaan" => $pekerjaan,
            "agama" => $agama,
            "statusNikah" => $statusNikah,
            "pendidikan" => $pendidikan,
            "negara" => $negara,
            "sukuBangsa" => $sukuBangsa,
            "noIdentitas" => $noIdentitas,
            "alamatKerabat" => $alamatKerabat,
        ] = Yii::$app->request->post();

        $data = [
            "no_rm" => $kodeRekamMedis,
            "nama" => $nama,
            "tempat_lahir" => $tempatLahir,
            "tanggal_lahir" => $toSystemDate($tanggalLahir),
            "jenis_kelamin" => $kelamin,
            "golongan_darah" => $golonganDarah,
            "alamat_jalan" => $alamatJalan,
            "alamat_rt" => $rt,
            "alamat_rw" => $rw,
            "alamat_kode_pos" => $kodePos,
            "no_telpon" => $noTelefon,
            "no_hp" => $noHp,
            "alamat_propinsi" => $propinsi,
            "alamat_kota" => $kota,
            "alamat_kecamatan" => $kecamatan,
            "alamat_kelurahan" => $kelurahan,
            "pekerjaan" => $pekerjaan,
            "agama" => $agama,
            "status_nikah" => $statusNikah,
            "pendidikan" => $pendidikan,
            "warga_negara" => $negara,
            "suku_bangsa" => $sukuBangsa,
            "nomor_id" => $noIdentitas,
            "kerabat_alamat_jalan" => $alamatKerabat,
        ];

        $validator = new \CI_Form_validation;
        $validator->set_rules("nama", "Nama", "required");
        $validator->set_rules("tempat_lahir", "Tempat Lahir", "required");
        $validator->set_rules("tanggal_lahir", "Tanggal Lahir", "required");
        $validator->set_error_delimiters('<p class="help-inline">');

        if ($validator->run()) {
            if (!(new PasienModel)->save($data)) throw new Exception("Fail to save data");
        } else {
            throw new Exception("Data is invalid");
        }
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/master/pasien.php#view    the original method
     */
    public function actionViewData(): string
    {
        $kodeRekamMedis = Yii::$app->request->post("kodeRekamMedis") ?? Yii::$app->request->post("id");
        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                pasien.id_pasien                AS idPasien,
                pasien.no_rm                    AS kodeRekamMedis,
                pasien.tgl_terbit_rm            AS tanggalTerbitRekamMedis,
                pasien.status                   AS status,
                pasien.nama                     AS nama,
                pasien.tempat_lahir             AS tempatLahir,
                pasien.tanggal_lahir            AS tanggalLahir,
                pasien.tanggal_mati             AS tanggalMati,
                pasien.umur_tahun               AS umurTahun,
                pasien.umur_bulan               AS umurBulan,
                pasien.jenis_kelamin            AS kelamin,
                pasien.golongan_darah           AS golonganDarah,
                pasien.gol_darah_old            AS golonganDarahOld,
                pasien.darah_resus              AS darahResus,
                pasien.alamat_jalan             AS alamatJalan,
                pasien.alamat_rt                AS alamatRt,
                pasien.alamat_rw                AS alamatRw,
                pasien.alamat_kelurahan         AS alamatKelurahan,
                pasien.alamat_kecamatan         AS alamatKecamatan,
                pasien.alamat_kota              AS alamatKota,
                pasien.alamat_propinsi          AS alamatPropinsi,
                pasien.alamat_kode_pos          AS alamatKodePos,
                pasien.no_telpon                AS noTelpon,
                pasien.no_hp                    AS noHp,
                pasien.pekerjaan                AS pekerjaan,
                pasien.agama                    AS agama,
                pasien.kawin                    AS kawin,
                pasien.status_nikah             AS statusNikah,
                pasien.pendidikan_old           AS pendidikanOld,
                pasien.pendidikan               AS pendidikan,
                pasien.id_pendidikan            AS idPendidikan,
                pasien.warga_negara             AS wargaNegara,
                pasien.suku_bangsa              AS sukuBangsa,
                pasien.nomor_id                 AS nomorId,
                pasien.nama_ayah                AS namaAyah,
                pasien.nama_ibu                 AS namaIbu,
                pasien.kerabat_alamat_jalan     AS kerabatAlamatJalan,
                pasien.kerabat_alamat_rt        AS kerabatAlamatRt,
                pasien.kerabat_alamat_rw        AS kerabatAlamatRw,
                pasien.kerabat_alamat_kelurahan AS kerabatAlamatKelurahan,
                pasien.kerabat_alamat_kecamatan AS kerabatAlamatKecamatan,
                pasien.kerabat_alamat_kota      AS kerabatAlamatKota,
                pasien.kerabat_alamat_propinsi  AS kerabatAlamatPropinsi,
                pasien.kerabat_alamat_kode_pos  AS kerabatAlamatKodePos,
                pasien.kerabat_no_telpon        AS kerabatNoTelefon,
                pasien.kerabat_no_hp            AS kerabatNoHp,
                pasien.cara_bayar               AS caraBayar,
                pasien.no_kartu_jaminan         AS noKartuJaminan,
                pasien.kelas_jaminan            AS kelasJaminan,
                pasien.alergi                   AS alergi,
                pasien.is_otc                   AS isOtc,
                pasien.user_input               AS userInput,
                pasien.gabung_rm                AS gabungRekamMedis,
                prop.nama_prop                  AS alamatNamaPropinsi,
                kota.nama_kota                  AS alamatNamaKota,
                kec.nama_camat                  AS alamatNamaCamat,
                kel.nama_lurah                  AS alamatNamaLurah,
                pek.nama_pekerjaan              AS namaPekerjaan,
                pend.nama_pendidikan            AS namaPendidikan,
                agama.nama_agama                AS namaAgama,
                neg.nama_negara                 AS namaNegara
            FROM simrs_bayangan.pasien
            LEFT JOIN simrs_bayangan.propinsi AS prop ON alamat_propinsi = id_prop
            LEFT JOIN simrs_bayangan.kota ON alamat_kota = id_kota
            LEFT JOIN simrs_bayangan.kecamatan AS kec ON alamat_kecamatan = id_camat
            LEFT JOIN simrs_bayangan.kelurahan AS kel ON alamat_kelurahan = id_lurah
            LEFT JOIN simrs_bayangan.agama ON agama = id_agama
            LEFT JOIN simrs_bayangan.pendidikan AS pend ON pendidikan = pend.id_pendidikan
            LEFT JOIN simrs_bayangan.pekerjaan AS pek ON pekerjaan = id_pekerjaan
            LEFT JOIN simrs_bayangan.negara AS neg ON warga_negara = id_negara
            WHERE
                no_rm = :kodeRekamMedis
                AND alamat_propinsi = kota.id_prop
                AND alamat_kota = kec.id_kota
                AND alamat_propinsi = kec.id_prop
                AND alamat_kecamatan = kel.id_camat
                AND alamat_kota = kel.id_kota
                AND alamat_propinsi = kel.id_prop
            LIMIT 1
        ";
        $params = [":kodeRekamMedis" => $kodeRekamMedis];
        $pasien = $connection->createCommand($sql, $params)->queryOne();
        return json_encode($pasien);
    }
}
