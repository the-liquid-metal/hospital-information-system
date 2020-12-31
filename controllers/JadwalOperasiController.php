<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\controllers;

use tlm\his\FatmaPharmacy\models\{AlatOperasiModel, FailToDeleteException, JadwalOperasiModel, JadwalOperasiRincianModel};
use tlm\his\FatmaPharmacy\views\JadwalOperasi\{LaporanDibuat, LaporanDisetujui};
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
class JadwalOperasiController extends BaseController
{
    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/jadwaloperasi.php#listjadwal    the original method
     */
    public function actionTableJadwalData(): string
    {
        [   "idDokter" => $idDokter,
            "idPoli" => $idPoli,
            "idInstalasi" => $idInstalasi,
            "persetujuan" => $persetujuan,
        ] = Yii::$app->request->post();

        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                CONCAT(TIME_FORMAT(JOP.rencana_operasi, '%H:%i'), ' - ', TIME_FORMAT(JOP.rencana_operasi_end, '%H:%i')) AS jam,
                JOP.id                         AS id,
                JOP.nama                       AS nama,
                JOP.no_rm                      AS kodeRekamMedis,
                JOP.umur                       AS umur,
                JOP.tanggal_lahir              AS tanggalLahir,
                JOP.jenis_kelamin              AS kelamin,
                JOP.no_telp                    AS noTelefon,
                JOP.gedung                     AS gedung,
                JOP.ruang                      AS ruang,
                JOP.kelas                      AS kelas,
                JOP.kelas_rm                   AS kelasRekamMedis,
                JOP.tempat_tidur               AS tempatTidur,
                JOP.tempat_tidur_id            AS idTempatTidur,
                JOP.cara_bayar                 AS caraBayar,
                JOP.jenis_cara_bayar           AS jenisCaraBayar,
                JOP.hubungan_keluarga_penjamin AS hubunganKeluargaPenjamin,
                JOP.no_peserta_jaminan         AS noPesertaJaminan,
                JOP.nama_peserta_jaminan       AS namaPesertaJaminan,
                JOP.asal_wilayah_jabotabek     AS asalWilayahJabotabek,
                JOP.asal_wilayah               AS asalWilayah,
                JOP.diagnosa                   AS diagnosa,
                JOP.tindakan                   AS tindakan,
                JOP.operator                   AS operator,
                JOP.id_dokter                  AS idDokter,
                JOP.dokter_bedah               AS dokterBedah,
                JOP.perawat_bedah              AS perawatBedah,
                JOP.dokter_anastesi            AS dokterAnastesi,
                JOP.penata_anastesi            AS penataAnastesi,
                JOP.rencana_operasi            AS rencanaOperasi,
                JOP.rencana_operasi_end        AS rencanaOperasiEnd,
                JOP.durasi_op                  AS durasiOperasi,
                JOP.smf                        AS smf,
                JOP.group_id                   AS idGroup,
                JOP.ruang_operasi              AS ruangOperasi,
                JOP.jenis_operasi              AS jenisOperasi,
                JOP.post_op                    AS pascaOperasi,
                JOP.status                     AS status,
                JOP.prioritas                  AS prioritas,
                JOP.infeksi                    AS infeksi,
                JOP.id_instalasi               AS idInstalasi,
                JOP.id_poli                    AS idPoli,
                JOP.request_akomodasi          AS requestAkomodasi,
                JOP.sysdate_in                 AS createdTime,
                JOP.sysdate_last               AS updatedTime,
                JOP.user_created               AS createdBy,
                JOP.user_last                  AS updatedBy,
                POL.nama_poli                  AS namaPoli
            FROM db1.jadwal_operasi AS JOP
            LEFT JOIN db1.master_poli AS POL ON POL.id_instalasi = JOP.id_instalasi
            WHERE
                POL.id_poli = JOP.id_poli
                AND (
                    (:persetujuan = 'disetujui' AND JOP.status = 1)
                    OR (JOP.status = 0)
                )
                AND DATE(JOP.rencana_operasi) >= :tanggalOperasi
                AND (:operator = '' OR JOP.operator = :operator)
                AND (:idPoli = '' OR JOP.id_poli = :idPoli)
                AND (:idInstalasi = '' OR JOP.id_poli = :idInstalasi)
        ";
        $params = [
            ":persetujuan" => $persetujuan,
            ":tanggalOperasi" => Yii::$app->dateTime->todayVal("system"),
            ":operator" => $idDokter,
            ":idPoli" => $idPoli,
            ":idInstalasi" => $idInstalasi,
        ];
        $daftarJadwalOperasi = $connection->createCommand($sql, $params)->queryAll();

        return json_encode($daftarJadwalOperasi);
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/jadwaloperasi.php#add    the original method
     */
    public function actionSaveAdd(): void
    {
        $toSystemDate = Yii::$app->dateTime->transformFunc("toSystemDate");
        $nowValSystem = Yii::$app->dateTime->nowVal("system");
        [   "rencana_operasi_tgl" => $tanggalRencanaOperasi,
            "rencana_operasi_end_tgl" => $tanggalRencanaOperasiEnd,
            "durasi" => $durasi,
            "tanggal_lahir" => $tanggalLahir,
            "nama" => $nama,
            "no_rm" => $kodeRekamMedis,
            "no_daftar" => $noPendaftaran,
            "jenis_kelamin" => $kelamin,
            "alamat" => $alamat,
            "no_telp" => $noTelefon,
            "operator" => $operator,
            "id_dokter" => $idDokter,
            "request_akomodasi" => $requestAkomodasi,
            "kelasrm" => $kelasRm,
            "ruang" => $ruang,
            "kelas" => $kelas,
            "tempat_tidur" => $tempatTidur,
            "tempat_tidur_id" => $idTempatTidur,
            "post_op" => $postOp,
            "jenis_operasi" => $jenisOperasi,
            "jenis_cara_bayar" => $jenisCaraBayar,
            "cara_bayar" => $caraBayar,
            "hubungan_keluarga_penjamin" => $hubunganKeluargaPenjamin,
            "no_peserta_jaminan" => $noPesertaJaminan,
            "nama_peserta_jaminan" => $namaPesertaJaminan,
            "asal_wilayah_jabotabek" => $asalWilayahJabotabek,
            "asal_wilayah" => $asalWilayah,
            "id_instalasi" => $idInstalasi,
            "id_poli" => $idPoli,
            "prioritas" => $prioritas,
            "infeksi" => $infeksi,
            "alat_operasi_nama" => $daftarNamaalatOperasi,
            "alat_operasi_qty" => $daftarKuantitasAlatOperasi,
            "alat_operasi_satuan" => $daftarSatuanAlatOperasi,
            "diagnosa" => $daftarDiagnosa,
            "kd_icd10" => $daftarKodeIcd10,
            "tindakan" => $daftarTindakan,
            "kd_icd9cm" => $daftarKodeIcd9cm,
        ] = Yii::$app->request->post();

        $idUser = Yii::$app->userFatma->id;
        $validator = new \CI_Form_validation;
        $validator->set_rules("nama", "Nama", "required");
        $validator->set_rules("no_rm", "No. RM", "required");
        $validator->set_error_delimiters('<p class="help-inline">');
        if ($validator->run()) throw new Exception("not valid");

        $tanggalLahir = $toSystemDate($tanggalLahir);

        $connection = Yii::$app->dbFatma;

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

        if (!$cekRekamMedis) {
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
                ":nama" => $nama,
                ":noPendaftaran" => $noPendaftaran,
                ":tanggalLahir" => $tanggalLahir,
                ":kelamin" => $kelamin,
                ":alamatJalan" => $alamat,
                ":noTelefon" => $noTelefon,
            ];
            $connection->createCommand($sql, $params)->execute();

        } else {
            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__." 
                -- LINE: ".__LINE__." 
                DELETE FROM db1.pasien_small
                WHERE no_rm = :kodeRekamMedis
            ";
            $params = [":kodeRekamMedis" => $kodeRekamMedis];
            $connection->createCommand($sql, $params)->execute();

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
                ":nama" => $nama,
                ":noPendaftaran" => $noPendaftaran,
                ":tanggalLahir" => $tanggalLahir,
                ":kelamin" => $kelamin,
                ":alamatJalan" => $alamat,
                ":noTelefon" => $noTelefon,
            ];
            $connection->createCommand($sql, $params)->execute();
        }

        $dataInput = [
            "no_rm" => $kodeRekamMedis,
            "nama" => $nama,
            "tanggal_lahir" => $toSystemDate($tanggalLahir),
            "no_daftar" => $noPendaftaran,
            "alamat" => $alamat,
            "jenis_kelamin" => $kelamin,
            "no_telp" => $noTelefon,
            "operator" => $operator,
            "id_dokter" => $idDokter,
            "kelas_rm" => $kelasRm,
            "ruang" => $ruang,
            "kelas" => $kelas,
            "tempat_tidur" => $tempatTidur,
            "tempat_tidur_id" => $idTempatTidur,
            "request_akomodasi" => $requestAkomodasi,
            "post_op" => $postOp,
            "jenis_cara_bayar" => $jenisCaraBayar,
            "cara_bayar" => $caraBayar,
            "hubungan_keluarga_penjamin" => $hubunganKeluargaPenjamin,
            "no_peserta_jaminan" => $noPesertaJaminan,
            "nama_peserta_jaminan" => $namaPesertaJaminan,
            "asal_wilayah_jabotabek" => $asalWilayahJabotabek,
            "asal_wilayah" => $asalWilayah,
            "jenis_operasi" => $jenisOperasi,
            "id_instalasi" => $idInstalasi,
            "id_poli" => $idPoli,
            "prioritas" => $prioritas,
            "infeksi" => $infeksi,
            "user_created" => $idUser,
            "rencana_operasi" => $toSystemDate($tanggalRencanaOperasi),
            "rencana_operasi_end" => $toSystemDate($tanggalRencanaOperasiEnd),
            "durasi_op" => $durasi,
            "sysdate_in" => $nowValSystem,
        ];

        if ((new JadwalOperasiModel)->save($dataInput)) {
            $idJadwalOperasi = $this->db->insert_id();

            foreach ($daftarNamaalatOperasi as $idx => $namaAlat) {
                if (!$namaAlat) continue;
                $dataAlataOperasi = [
                    "id_jadwal_operasi" => $idJadwalOperasi,
                    "nama" => $namaAlat,
                    "jumlah" => $daftarKuantitasAlatOperasi[$idx],
                    "satuan" => $daftarSatuanAlatOperasi[$idx],
                ];
                (new AlatOperasiModel)->save($dataAlataOperasi);
            }

            foreach ($daftarDiagnosa as $idx => $diagnosa) {
                if (!$diagnosa) continue;
                $dataDiagnosaOperasi = [
                    "id_jadwaloperasi" => $idJadwalOperasi,
                    "kode_diagtind" => $daftarKodeIcd10[$idx],
                    "diag_tind" => $diagnosa,
                    "kode" => "D",
                    "sysdate_in" => $nowValSystem,
                    "user_in" => $idUser
                ];
                (new JadwalOperasiRincianModel)->save($dataDiagnosaOperasi);
            }

            foreach ($daftarTindakan as $idx => $tindakan) {
                if (!$tindakan) continue;
                $dataTindakanOperasi = [
                    "id_jadwaloperasi" => $idJadwalOperasi,
                    "kode_diagtind" => $daftarKodeIcd9cm[$idx],
                    "diag_tind" => $tindakan,
                    "kode" => "T",
                    "sysdate_in" => $nowValSystem,
                    "user_in" => $idUser
                ];
                (new JadwalOperasiRincianModel)->save($dataTindakanOperasi);
            }
            // sukses

        } else {
            // gagal
        }
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws \Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/jadwaloperasi.php#edit    the original method
     */
    public function actionSaveEdit(): void
    {
        [   "operator" => $operator,
            "id" => $id,
            "id_dokter" => $idDokter,
            "request_akomodasi" => $requestAkomodasi,
            "post_op" => $postOp,
            "jenis_operasi" => $jenisOperasi,
            "prioritas" => $prioritas,
            "infeksi" => $infeksi,
            "rencana_operasi_tgl" => $tanggalRencanaOperasi,
            "rencana_operasi_jam" => $jamRencanaOperasi,
            "rencana_operasi_end_tgl" => $tanggalRencanaOperasiEnd,
            "rencana_operasi_end_jam" => $jamRencanaOperasiEnd,
            "status" => $status,
            "alat_operasi_nama" => $daftarNamaalatOperasi,
            "alat_operasi_qty" => $daftarKuantitasAlatOperasi,
            "alat_operasi_satuan" => $daftarSatuanAlatOperasi,
            "diagnosa" => $daftarDiagnosa,
            "kd_icd10" => $daftarKodeIcd10,
            "tindakan" => $daftarTindakan,
            "kd_icd9cm" => $daftarKodeIcd9cm,
        ] = Yii::$app->request->post();
        $dateTime = Yii::$app->dateTime;
        $toSystemPartialDatetime = $dateTime->transformFunc("toSystemPartialDatetime");
        $nowValSystem = $dateTime->nowVal("system");
        $idUser = Yii::$app->userFatma->id;

        $validator = new \CI_Form_validation;
        $validator->set_rules("nama", "Nama", "required");
        $validator->set_rules("no_rm", "No. RM", "required");
        $validator->set_error_delimiters('<p class="help-inline">');
        if (!$validator->run()) throw new \Exception("Fail to save");

        $dataJadwalOperasi = [
            "id" => $id,
            "operator" => $operator,
            "id_dokter" => $idDokter,
            "request_akomodasi" => $requestAkomodasi,
            "post_op" => $postOp,
            "jenis_operasi" => $jenisOperasi,
            "prioritas" => $prioritas,
            "infeksi" => $infeksi,
            "user_last" => $idUser,
            "rencana_operasi" => $toSystemPartialDatetime("$tanggalRencanaOperasi  $jamRencanaOperasi"),
            "rencana_operasi_end" => $toSystemPartialDatetime("$tanggalRencanaOperasiEnd  $jamRencanaOperasiEnd"),
            "status" => $status,
        ];

        $ubahData = (new JadwalOperasiModel)->ubah($dataJadwalOperasi);
        if ($ubahData == 1 || $ubahData == 0) {
            (new AlatOperasiModel)->clear($id);
            (new JadwalOperasiRincianModel)->clear($id);

            foreach ($daftarNamaalatOperasi as $idx => $namaAlat) {
                if (!$namaAlat) continue;
                $dataAlatOperasi = [
                    "id_jadwal_operasi" => $id,
                    "nama" => $namaAlat,
                    "jumlah" => $daftarKuantitasAlatOperasi[$idx],
                    "satuan" => $daftarSatuanAlatOperasi[$idx]
                ];
                (new AlatOperasiModel)->save($dataAlatOperasi);
            }

            foreach ($daftarDiagnosa as $idx => $diagnosa) {
                if (!$diagnosa) continue;
                $dataDiagnosaOperasi = [
                    "id_jadwaloperasi" => $id,
                    "kode_diagtind" => $daftarKodeIcd10[$idx],
                    "diag_tind" => $diagnosa,
                    "kode" => "D",
                    "sysdate_in" => $nowValSystem,
                    "user_in" => $idUser
                ];
                (new JadwalOperasiRincianModel)->save($dataDiagnosaOperasi);
            }

            foreach ($daftarTindakan as $idx => $tindakan) {
                if (!$tindakan) continue;
                $dataTindakanOperasi = [
                    "id_jadwaloperasi" => $id,
                    "kode_diagtind" => $daftarKodeIcd9cm[$idx],
                    "diag_tind" => $tindakan,
                    "kode" => "T",
                    "sysdate_in" => $nowValSystem,
                    "user_in" => $idUser
                ];
                (new JadwalOperasiRincianModel)->save($dataTindakanOperasi);
            }
            // sukses

        } else {
            // gagal
        }
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/jadwaloperasi.php#edit    the original method
     */
    public function actionEditData(): string
    {
        $id = Yii::$app->request->post("id") ?? throw new MissingPostParamException("id");
        $dateTime = Yii::$app->dateTime;
        $toSystemDate = $dateTime->transformFunc("toSystemDate");

        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                id                         AS id,
                nama                       AS nama,
                no_rm                      AS kodeRekamMedis,
                umur                       AS umur,
                tanggal_lahir              AS tanggalLahir,
                jenis_kelamin              AS kelamin,
                no_telp                    AS noTelefon,
                gedung                     AS gedung,
                ruang                      AS ruang,
                kelas                      AS kelas,
                kelas_rm                   AS kelasRekamMedis,
                tempat_tidur               AS tempatTidur,
                tempat_tidur_id            AS idTempatTidur,
                cara_bayar                 AS caraBayar,
                jenis_cara_bayar           AS jenisCaraBayar,
                hubungan_keluarga_penjamin AS hubunganKeluargaPenjamin,
                no_peserta_jaminan         AS noPesertaJaminan,
                nama_peserta_jaminan       AS namaPesertaJaminan,
                asal_wilayah_jabotabek     AS asalWilayahJabotabek,
                asal_wilayah               AS asalWilayah,
                diagnosa                   AS diagnosa,
                tindakan                   AS tindakan,
                operator                   AS operator,
                id_dokter                  AS idDokter,
                dokter_bedah               AS dokterBedah,
                perawat_bedah              AS perawatBedah,
                dokter_anastesi            AS dokterAnastesi,
                penata_anastesi            AS penataAnastesi,
                rencana_operasi            AS rencanaOperasi,
                rencana_operasi_end        AS rencanaOperasiEnd,
                durasi_op                  AS durasiOperasi,
                smf                        AS smf,
                group_id                   AS idGroup,
                ruang_operasi              AS ruangOperasi,
                jenis_operasi              AS jenisOperasi,
                post_op                    AS pascaOperasi,
                status                     AS status,
                prioritas                  AS prioritas,
                infeksi                    AS infeksi,
                id_instalasi               AS idInstalasi,
                id_poli                    AS idPoli,
                request_akomodasi          AS requestAkomodasi,
                sysdate_in                 AS createdTime,
                sysdate_last               AS updatedTime,
                user_created               AS createdBy,
                user_last                  AS updatedTime
            FROM db1.jadwal_operasi
            WHERE id = :id
        ";
        $params = [":id" => $id];
        $jadwalOperasi = $connection->createCommand($sql, $params)->queryOne();
        $jadwalOperasi->rencana_operasi_tgl = $toSystemDate($jadwalOperasi->rencana_operasi);
        $jadwalOperasi->rencana_operasi_jam = date("H:i", strtotime($jadwalOperasi->rencana_operasi));
        $jadwalOperasi->rencana_operasi_end_tgl = $toSystemDate($jadwalOperasi->rencana_operasi_end);
        $jadwalOperasi->rencana_operasi_end_jam = date("H:i", strtotime($jadwalOperasi->rencana_operasi_end));

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                id                AS id,
                id_jadwal_operasi AS idJadwalOperasi,
                nama              AS nama,
                jumlah            AS jumlah,
                satuan            AS satuan
            FROM db1.alat_operasi
            WHERE id_jadwal_operasi = :idJadwalOperasi
        ";
        $params = [":idJadwalOperasi" => $id];
        $daftarAlatOperasi = $connection->createCommand($sql, $params)->queryAll();

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                id               AS id,
                id_jadwaloperasi AS idJadwalOperasi,
                kode_diagtind    AS kodeDiagnosaTindakan,
                diag_tind        AS diagnosaTindakan,
                kode             AS kode,
                sysdate_in       AS createdTime,
                user_in          AS createdBy,
                sysdate_last     AS updatedTime,
                user_last        AS updatedBy
            FROM db1.jadwaloperasi_rinc
            WHERE
                id_jadwaloperasi = :idJadwalOperasi
                AND kode = 'D'
        ";
        $params = [":idJadwalOperasi" => $id];
        $daftarDiagnosaOperasi = $connection->createCommand($sql, $params)->queryAll();

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                id               AS id,
                id_jadwaloperasi AS idJadwalOperasi,
                kode_diagtind    AS kodeDiagnosaTindakan,
                diag_tind        AS diagnosaTindakan,
                kode             AS kode,
                sysdate_in       AS createdTime,
                user_in          AS createdBy,
                sysdate_last     AS updatedTime,
                user_last        AS updatedBy
            FROM db1.jadwaloperasi_rinc
            WHERE
                id_jadwaloperasi = :idJadwalOperasi
                AND kode = 'T'
        ";
        $params = [":idJadwalOperasi" => $id];
        $daftarTindakanOperasi = $connection->createCommand($sql, $params)->queryAll();

        return json_encode([
            "data" => $jadwalOperasi,
            "daftarDiagnosaOperasi" => $daftarDiagnosaOperasi,
            "daftarTindakanOperasi" => $daftarTindakanOperasi,
            "daftarAlatOperasi" => $daftarAlatOperasi,
        ]);
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/jadwaloperasi.php#select    the original method
     */
    public function actionSelect(): string
    {
        ["q" => $val] = Yii::$app->request->post();
        $connection = Yii::$app->dbFatma;

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT -- all are in use.
                KAT.id              AS id, -- this is actually not used
                KAT.kode            AS kode,
                KAT.nama_sediaan    AS namaSediaan,
                KAT.formularium_rs  AS formulariumRs,
                KAT.formularium_nas AS formulariumNas,
                PBK.nama_pabrik     AS namaPabrik
            FROM db1.masterf_katalog AS KAT
            LEFT JOIN db1.masterf_pabrik AS PBK ON PBK.id = KAT.id_pabrik
            WHERE
                KAT.kode LIKE :val
                OR KAT.nama_sediaan LIKE :val
                AND KAT.id_kelompokbarang = 14
            ORDER BY nama_sediaan ASC
            LIMIT 30
        ";
        $params = [":val" => "%$val%"];
        $daftarKatalog = $connection->createCommand($sql, $params)->queryAll();

        return json_encode($daftarKatalog);
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @throws FailToDeleteException
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/jadwaloperasi.php#Delete    the original method
     */
    public function actionDelete(): string
    {
        $id = Yii::$app->request->post("id") ?? throw new MissingPostParamException("id");

        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                id                         AS id,
                nama                       AS nama,
                no_rm                      AS kodeRekamMedis,
                umur                       AS umur,
                tanggal_lahir              AS tanggalLahir,
                jenis_kelamin              AS kelamin,
                no_telp                    AS noTelefon,
                gedung                     AS gedung,
                ruang                      AS ruang,
                kelas                      AS kelas,
                kelas_rm                   AS kelasRekamMedis,
                tempat_tidur               AS tempatTidur,
                tempat_tidur_id            AS idTempatTidur,
                cara_bayar                 AS caraBayar,
                jenis_cara_bayar           AS jenisCaraBayar,
                hubungan_keluarga_penjamin AS hubunganKeluargaPenjamin,
                no_peserta_jaminan         AS noPesertaJaminan,
                nama_peserta_jaminan       AS namaPesertaJaminan,
                asal_wilayah_jabotabek     AS asalWilayahJabotabek,
                asal_wilayah               AS asalWilayah,
                diagnosa                   AS diagnosa,
                tindakan                   AS tindakan,
                operator                   AS operator,
                id_dokter                  AS idDokter,
                dokter_bedah               AS dokterBedah,
                perawat_bedah              AS perawatBedah,
                dokter_anastesi            AS dokterAnastesi,
                penata_anastesi            AS penataAnastesi,
                rencana_operasi            AS rencanaOperasi,
                rencana_operasi_end        AS rencanaOperasiEnd,
                durasi_op                  AS durasiOperasi,
                smf                        AS smf,
                group_id                   AS idGroup,
                ruang_operasi              AS ruangOperasi,
                jenis_operasi              AS jenisOperasi,
                post_op                    AS pascaOperasi,
                status                     AS status,
                prioritas                  AS prioritas,
                infeksi                    AS infeksi,
                id_instalasi               AS idInstalasi,
                id_poli                    AS idPoli,
                request_akomodasi          AS requestAkomodasi,
                sysdate_in                 AS createdTime,
                sysdate_last               AS updatedTime,
                user_created               AS createdBy,
                user_last                  AS updatedBy
            FROM db1.jadwal_operasi
            WHERE id = :id
        ";
        $params = [":id" => $id];
        $jadwalOperasi = $connection->createCommand($sql, $params)->queryOne();

        if ($jadwalOperasi->createdBy != Yii::$app->userFatma->id && ! $this->get("isAdmin") && ! $this->isOperator) {
            throw new FailToDeleteException($id);
        }

        if ((new JadwalOperasiModel)->delete($id)) {
            (new AlatOperasiModel)->clear($id);
            (new JadwalOperasiRincianModel)->clear($id);
            return "";
        } else {
            throw new FailToDeleteException($id);
        }
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/jadwaloperasi.php#booking    the original method
     */
    public function actionTableBookingData(): string
    {
        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                JOP.id                         AS id,
                JOP.nama                       AS nama,
                JOP.no_rm                      AS kodeRekamMedis,
                JOP.umur                       AS umur,
                JOP.tanggal_lahir              AS tanggalLahir,
                JOP.jenis_kelamin              AS kelamin,
                JOP.no_telp                    AS noTelefon,
                JOP.gedung                     AS gedung,
                JOP.ruang                      AS ruang,
                JOP.kelas                      AS kelas,
                JOP.kelas_rm                   AS kelasRekamMedis,
                JOP.tempat_tidur               AS tempatTidur,
                JOP.tempat_tidur_id            AS idTempatTidur,
                JOP.cara_bayar                 AS caraBayar,
                JOP.jenis_cara_bayar           AS jenisCaraBayar,
                JOP.hubungan_keluarga_penjamin AS hubunganKeluargaPenjamin,
                JOP.no_peserta_jaminan         AS noPesertaJaminan,
                JOP.nama_peserta_jaminan       AS namaPesertaJaminan,
                JOP.asal_wilayah_jabotabek     AS asalWilayahJabotabek,
                JOP.asal_wilayah               AS asalWilayah,
                JOP.diagnosa                   AS diagnosa,
                JOP.tindakan                   AS tindakan,
                JOP.operator                   AS operator,
                JOP.id_dokter                  AS idDokter,
                JOP.dokter_bedah               AS dokterBedah,
                JOP.perawat_bedah              AS perawatBedah,
                JOP.dokter_anastesi            AS dokterAnastesi,
                JOP.penata_anastesi            AS penataAnastesi,
                JOP.rencana_operasi            AS rencanaOperasi,
                JOP.rencana_operasi_end        AS rencanaOperasiEnd,
                JOP.durasi_op                  AS durasiOperasi,
                JOP.smf                        AS smf,
                JOP.group_id                   AS idGroup,
                JOP.ruang_operasi              AS ruangOperasi,
                JOP.jenis_operasi              AS jenisOperasi,
                JOP.post_op                    AS pascaOperasi,
                JOP.status                     AS status,
                JOP.prioritas                  AS prioritas,
                JOP.infeksi                    AS infeksi,
                JOP.id_instalasi               AS idInstalasi,
                JOP.id_poli                    AS idPoli,
                JOP.request_akomodasi          AS requestAkomodasi,
                JOP.sysdate_in                 AS createdTime,
                JOP.sysdate_last               AS updatedTime,
                JOP.user_created               AS createdBy,
                JOP.user_last                  AS updatedBy,
                POL.nama_poli                  AS namaPoli
            FROM db1.jadwal_operasi AS JOP
            LEFT JOIN db1.master_poli AS POL ON POL.id_instalasi = JOP.id_instalasi
            WHERE
                JOP.status = 0
                AND POL.id_poli = JOP.id_poli
        ";
        $daftarJadwalOperasi = $connection->createCommand($sql)->queryAll();

        $baseUrl = Yii::$app->urlManager->baseUrl.'/';
        $module = $this->module->id;

        $daftarField = [
            "rencana_operasi" => "Tanggal",
            "no_rm" => "No. RM",
            "nama" => "Nama Pasien",
            "operator" => "Operator",
            "nama_poli" => "Poli",
            "ruang" => "Ruang Rawat",
            "kelas" => "Kamar/Kelas",
            "tempat_tidur" => "Tempat Tidur",
        ];

        $action =
            '<a class="btn btn-mini btn-primary approve-btn" href="' . $baseUrl.'/'.$module.'/jadwal-operasi/approved/%1$s">
                <i class="icon-ok icon-white"></i> Setujui
            </a>
            <a class="btn btn-mini editruangrawat-btn" href="' . $baseUrl.'/'.$module.'/jadwal-operasi/edit-ruang-rawat/%1$s">
                <i class="icon-pencil"></i> R. Rawat
            </a>
            <a class="btn btn-mini edit-btn" href="' . $baseUrl.'/'.$module.'/jadwal-operasi/edit/%1$s">
                <i class="icon-pencil"></i> Edit
            </a>
            <a class="btn btn-mini btn-danger lnk-delete" data-toggle="modal" href="#delete-confirm2" data-url="' . $baseUrl.'/'.$module.'/jadwal-operasi/delete/%1$s">
                <i class="icon-remove icon-white"></i> Delete
            </a> ';

        return json_encode([
            "daftarJadwalOperasi" => $daftarJadwalOperasi,
            "daftarField" => $daftarField,
            "action" => $action,
            "rescheduleData" => (new JadwalOperasiModel)->findJadwalReschedule("status = 2"),
        ]);
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see    http://localhost/ori-source/fatma-pharmacy/controllers/jadwaloperasi.php#get_ruang_operasi    the original method
     */
    public function actionGetRuangOperasi(): string
    {
        ["tgl" => $tanggal, "tgl_end" => $tanggalAkhir] = Yii::$app->request->post();

        $search = ["_", "."];
        $replacement = [" ", ":"];
        $tanggal = date("Y-m-d H:i", strtotime(str_replace($search, $replacement, urldecode($tanggal))));
        $tanggalAkhir = date("Y-m-d H:i", strtotime(str_replace($search, $replacement, urldecode($tanggalAkhir))));

        $jo = new JadwalOperasiModel;
        $ruangOperasi = $jo->findRuangOperasi($tanggal, $tanggalAkhir);

        if ($ruangOperasi) {
            return json_encode([
                "ruang_operasi" => (array) $ruangOperasi
            ]);
        } else {
            return json_encode([
                "ruang_operasi" => (array) $ruangOperasi,
                "alert" => "Tidak ada ruang operasi yang kosong!"
            ]);
        }
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/jadwaloperasi.php#find_operator    the original method
     */
    public function actionFindOperator(): string
    {
        ["q" => $val] = Yii::$app->request->post();

        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                kode,
                nama
            FROM simrs_bayangan.kode_dokter
            WHERE nama LIKE :nama
        ";
        $params = [":nama" => "%$val%"];
        $daftarDokter = $connection->createCommand($sql, $params)->queryAll();
        return json_encode($daftarDokter);
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/jadwaloperasi.php#find_operator2    the original method
     */
    public function actionFindOperator2(): string
    {
        ["q" => $val, "kode_smf" => $kodeSmf] = Yii::$app->request->post();
        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__."
            -- LINE: ".__LINE__."
            SELECT
                kd_penerima AS id,
                kd_penerima AS kode,
                nm_penerima AS nama
            FROM db1.mmas_penerima
            WHERE
                kd_smf IN (:kodeSmf, 024)
                AND nm_penerima LIKE :nama
                AND sts_aktif = 1
        ";
        $params = [":kodeSmf" => $kodeSmf, ":nama" => "%$val%"];
        $dokter = $connection->createCommand($sql, $params)->queryAll();
        return json_encode($dokter);
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/jadwaloperasi.php#find_operator_add    the original method
     */
    public function actionFindOperatorAdd(): string
    {
        $val = Yii::$app->request->post("q");

        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                kd_penerima AS id,
                kd_penerima AS kode,
                nm_penerima AS nama
            FROM db1.mmas_penerima
            WHERE
                kd_smf IN (002, 004, 005, 006, 010, 011, 024)
                AND nm_penerima LIKE :nama
                AND sts_aktif = 1
        ";
        $params = [":nama" => "%$val%"];
        $daftarDokter = $connection->createCommand($sql, $params)->queryAll();
        return json_encode($daftarDokter);
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/jadwaloperasi.php#testbridge the original method
     */
    public static function testBridge(array $data_post): array
    {
        $url = "http://202.137.25.13/bridging/Bridging_ksorad.php";

        $data = ["fungsi" => $data_post["fungsi"]];

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

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @see    http://localhost/ori-source/fatma-pharmacy/controllers/jadwaloperasi.php#index    the original method
     */
    public function actionTableData(): string
    {
        $toSystemDate = Yii::$app->dateTime->transformFunc("toSystemDate");

        $operator = Yii::$app->request->post("operator");
        $filter = $operator ? "AND operator = '$operator'" : "";

        $hari = [
            "Senin",
            "Selasa",
            "Rabu",
            "Kamis",
            "Jumat",
            "Sabtu",
            "Minggu"
        ];

        // TODO: php: refactor: uri to something else in Yii
        $ruangOperasi = (int) $this->uri->segment(3) ?: 1;
        $year = $this->uri->segment(4);
        $week = $this->uri->segment(5);

        if ($year && $week) {
            $startDate = date("o-m-d", strtotime($year . "W" . $week . "1"));
            $tanggalAkhir = date("o-m-d", strtotime($year . "W" . $week . "6"));
        } else {
            $year = date("Y");
            $week = date("W");
            $startDate = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - (date("w") - 1), date("Y")));
            $tanggalAkhir = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") + (6 - date("w")), date("Y")));
        }

        $jadwalOperasi = (array) (new JadwalOperasiModel)->findJadwal("date(rencana_operasi) >= '$startDate' AND ruang_operasi = $ruangOperasi AND status = 1 $filter");

        $data = [];
        foreach ($jadwalOperasi as $row) {
            $dd = $toSystemDate($row->rencana_operasi);
            $colspan = ($row->hari_end - $row->hari) + 1;
            $jj = date("H", strtotime($row->jam));
            $rowspan = date("H", strtotime($row->jam_end)) - date("H", strtotime($row->jam));
            $data[$dd][$jj] = [
                "rowspan" => $rowspan,
                "colspan" => $colspan,
                "data" => $row,
            ];
        }

        $prevWeek = $week - 1;
        $nextWeek = $week + 1;
        $prevYear = $nextYear = $year;
        if ($prevWeek < 1) {
            $prevWeek = 52;
            $prevYear--;
        }

        if ($nextWeek > 52) {
            $nextWeek = 1;
            $nextYear++;
        }

        $prev = date("o/W", strtotime($prevYear . "W" . str_pad($prevWeek, 2, "0", STR_PAD_LEFT)));
        $next = date("o/W", strtotime($nextYear . "W" . str_pad($nextWeek, 2, "0", STR_PAD_LEFT)));
        $today = (date("W") == $week);

        /* start original view logic */
        $startDay = date("d", strtotime($startDate));
        $startMonth = date("m", strtotime($startDate));
        $startYear = date("y", strtotime($startDate));

        $rowspan = [];
        $selected = [];
        $dateAll = [];
        $hourAll = [];
        $activeClass = [];
        $html5attribute = [];
        $span = [];

        for ($j = 0; $j <= 23; $j++) {
            $jj = date("H", strtotime($j . ":00"));
            $hourAll[$j] = $jj;

            foreach ($hari as $idx => $h) {
                unset($h);
                $selected[$j][$idx] = ($startDay + $idx == date("d")) ? "selected" : "";
                $dd = date("Y-m-d", mktime(0, 0, 0, $startMonth, $startDay + $idx, $startYear));
                $dateAll[$j][$idx] = $dd;

                $rowspan[$idx] ??= 1;

                if ($rowspan[$idx] > 1) {
                    $rowspan[$idx]--;
                }

                if ($data[$dd][$jj]) {
                    $rowspan[$idx] = $data[$dd][$jj]["rowspan"] + 1;
                    $rowspan2 = $data[$dd][$jj]["rowspan"] > 1 ? " rowspan=\"{$data[$dd][$jj]['rowspan']}\"" : "";
                    $colspan2 = $data[$dd][$jj]["colspan"] > 1 ? " colspan=\"{$data[$dd][$jj]['colspan']}\"" : "";

                    $span[$j][$idx] = $rowspan2 . $colspan2;

                    $activeClass[$j][$idx] = " active "
                        . strtolower($data[$dd][$jj]["data"]->jenis_operasi)
                        . $data[$dd][$jj]["data"]->infeksi;

                    $content =
                        "<table class='table table-jadwal'>
                            <tr>
                                <th>No. RM</th>
                                <td>{$data[$dd][$jj]['data']->no_rm} </td>
                            </tr>
                            <tr>
                                <th>Diagnosa</th>
                                <td>{$data[$dd][$jj]['data']->diag} </td>
                            </tr>
                            <tr>
                                <th>Tindakan</th>
                                <td>{$data[$dd][$jj]['data']->tind} </td>
                            </tr>
                            <tr>
                                <th>Operator</th>
                                <td>{$data[$dd][$jj]['data']->operator}</td>
                            </tr>
                        </table>";

                    $html5attribute[$j][$idx] = "rel=\"popover\" data-id=\"{$data[$dd][$jj]['data']->id}\" data-original-title=\"{$data[$dd][$jj]['data']->nama}\" data-content=\"{$content}\" ";
                } else {
                    $activeClass[$j][$idx] = "";
                    $html5attribute[$j][$idx] = "";
                    $span[$j][$idx] = "";
                }
            }
        }
        /* end original view logic */

        return json_encode([
            "ruangOperasi" => $ruangOperasi,
            "today" => $today,
            "prev" => $prev,
            "startDate" => $startDate,
            "endDate" => $tanggalAkhir,
            "next" => $next,
            "daftarHari" => $hari,
            "data" => $data,
            "startDay" => (int) $startDay,
            "startMonth" => (int) $startMonth,
            "startYear" => (int) $startYear,
            "hourAll" => $hourAll,
            "dateAll" => $dateAll,
            "rowspan" => $rowspan,
            "selected" => $selected,
            "activeClass" => $activeClass,
            "span" => $span,
            "html5attribute" => $html5attribute,
        ]);
    }

    /**
     * @author Hendra Gunawan
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/jadwaloperasi.php#editruangrawat    the original method
     */
    public function actionSaveEditRuangRawat(): string
    {
        ["id" => $id, "t_rawat" => $tRawat, "tempat_tidur" => $tempatTidur] = Yii::$app->request->post();
        $tRawat = explode(":", $tRawat);
        $gedungRuang = explode("-", $tRawat[0]);
        $data = [
            "id" => $id,
            "gedung" => $gedungRuang[0],
            "ruang" => $gedungRuang[1],
            "kelas" => $tRawat[1],
            "tempat_tidur" => $tempatTidur,
        ];
        $return = (new JadwalOperasiModel)->save($data);
        return json_encode(["return" => !!$return]);
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/jadwaloperasi.php#editruangrawat    the original method
     */
    public function actionEditRuangRawatData(): Response|string
    {
        $dataHasil = static::testBridge(["fungsi" => "getDataKamar"]);
        $id = Yii::$app->request->post("id") ?? throw new MissingPostParamException("id");

        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                JOP.id                         AS id,
                JOP.nama                       AS nama,
                JOP.no_rm                      AS kodeRekamMedis,
                JOP.umur                       AS umur,
                JOP.tanggal_lahir              AS tanggalLahir,
                JOP.jenis_kelamin              AS kelamin,
                JOP.no_telp                    AS noTelefon,
                JOP.gedung                     AS gedung,
                JOP.ruang                      AS ruang,
                JOP.kelas                      AS kelas,
                JOP.kelas_rm                   AS kelasRekamMedis,
                JOP.tempat_tidur               AS tempatTidur,
                JOP.tempat_tidur_id            AS idTempatTidur,
                JOP.cara_bayar                 AS caraBayar,
                JOP.jenis_cara_bayar           AS jenisCaraBayar,
                JOP.hubungan_keluarga_penjamin AS hubunganKeluargaPenjamin,
                JOP.no_peserta_jaminan         AS noPesertaJaminan,
                JOP.nama_peserta_jaminan       AS namaPesertaJaminan,
                JOP.asal_wilayah_jabotabek     AS asalWilayahJabotabek,
                JOP.asal_wilayah               AS asalWilayah,
                JOP.diagnosa                   AS diagnosa,
                JOP.tindakan                   AS tindakan,
                JOP.operator                   AS operator,
                JOP.id_dokter                  AS idDokter,
                JOP.dokter_bedah               AS dokterBedah,
                JOP.perawat_bedah              AS perawatBedah,
                JOP.dokter_anastesi            AS dokterAnastesi,
                JOP.penata_anastesi            AS penataAnastesi,
                JOP.rencana_operasi            AS rencanaOperasi,
                JOP.rencana_operasi_end        AS rencanaOperasiEnd,
                JOP.durasi_op                  AS durasiOperasi,
                JOP.smf                        AS smf,
                JOP.group_id                   AS idGroup,
                JOP.ruang_operasi              AS ruangOperasi,
                JOP.jenis_operasi              AS jenisOperasi,
                JOP.post_op                    AS pascaOperasi,
                JOP.status                     AS status,
                JOP.prioritas                  AS prioritas,
                JOP.infeksi                    AS infeksi,
                JOP.id_instalasi               AS idInstalasi,
                JOP.id_poli                    AS idPoli,
                JOP.request_akomodasi          AS requestAkomodasi,
                JOP.sysdate_in                 AS createdTime,
                JOP.sysdate_last               AS updatedTime,
                JOP.user_created               AS createdBy,
                JOP.user_last                  AS updatedBy,
                ROP.nama                       AS namaRuangOperasiNama,
                INS.nama_instalasi             AS namaInstalasi,
                POL.nama_poli                  AS namaPoli
            FROM db1.jadwal_operasi AS JOP
            LEFT JOIN db1.master_ruang_operasi AS ROP ON ROP.id = JOP.ruang_operasi
            LEFT JOIN db1.master_instalasi AS INS ON INS.id_instalasi = JOP.id_instalasi   
            LEFT JOIN db1.master_poli AS POL ON POL.id_instalasi = JOP.id_instalasi
            WHERE
                JOP.id = :idJadwalOperasi
                AND JOP.status = 0
                AND POL.id_poli = JOP.id_poli
        ";
        $params = [":idJadwalOperasi" => $id];
        $daftarJadwalOperasi = $connection->createCommand($sql, $params)->queryAll();

        if (!$daftarJadwalOperasi) return "tidak ada data";

        return json_encode(["daftarJadwalOperasi" => $daftarJadwalOperasi, "daftarKamar" => $dataHasil]);
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/jadwaloperasi.php#laporan    the original method
     */
    public function actionTableReportData(): string
    {
        $todayValSystem = Yii::$app->dateTime->todayVal("system");

        $post = Yii::$app->request->post();
        [   "tanggal_awal" => $tanggalAwal,
            "tanggal_akhir" => $tanggalAkhir,
            "dokter" => $dokter,
            "laporan" => $laporan,
        ] = $post;

        $tanggalAwal = $tanggalAwal ?:  "$todayValSystem 00:00:00";
        $tanggalAkhir = $tanggalAkhir ?:  "$todayValSystem 23:59:59";

        $connection = Yii::$app->dbFatma;

        if ($laporan == "disetujui") {
            $result = (new JadwalOperasiModel)->cariLaporan($post);
            if (!$result) return "tidak ada data";

            $daftarHalaman = [];
            $h = 0; // index halaman
            $b = 0; // index baris
            $barisPerHalaman = 10;

            foreach ($result as $i => $row) {
                $sql = /** @lang SQL */ "
                    -- FILE: ".__FILE__." 
                    -- LINE: ".__LINE__." 
                    SELECT diag_tind
                    FROM db1.jadwaloperasi_rinc
                    WHERE
                        id_jadwaloperasi = :idJadwalOperasi
                        AND kode = 'D'
                ";
                $params = [":idJadwalOperasi" => $row->id];
                $daftarDiagnosa = $connection->createCommand($sql, $params)->queryAll();

                $sql = /** @lang SQL */ "
                    -- FILE: ".__FILE__." 
                    -- LINE: ".__LINE__." 
                    SELECT diag_tind
                    FROM db1.jadwaloperasi_rinc
                    WHERE
                        id_jadwaloperasi = :idJadwalOperasi
                        AND kode = 'T'
                ";
                $params = [":idJadwalOperasi" => $row->id];
                $daftarTindakan = $connection->createCommand($sql, $params)->queryAll();

                $daftarHalaman[$h][$b] = [
                    "i" => $i,
                    "diagnosa" => $daftarDiagnosa,
                    "tindakan" => $daftarTindakan,
                ];

                if ($b > $barisPerHalaman) {
                    $h++;
                    $b = 0;
                } else {
                    $b++;
                }
            }

            $view = new LaporanDisetujui(
                daftarHalaman: $daftarHalaman,
                tanggalAwal:   $tanggalAwal,
                tanggalAkhir:  $tanggalAkhir,
                namaDokter:    $dokter ?: "Semua Dokter",
                result:        $result,
                jumlahHalaman: count($daftarHalaman),
            );
            return $view->__toString();

        } else {
            $result = (new JadwalOperasiModel)->cariLaporan2($post);

            $daftarHalaman = [];

            $h = 0; // index halaman
            $b = 0; // index baris
            $barisPerHalaman = 8;

            foreach ($result as $row) {
                $sql = /** @lang SQL */ "
                    -- FILE: ".__FILE__." 
                    -- LINE: ".__LINE__." 
                    SELECT diag_tind
                    FROM db1.jadwaloperasi_rinc
                    WHERE
                        id_jadwaloperasi = :idJadwalOperasi
                        AND kode = 'D'
                ";
                $params = [":idJadwalOperasi" => $row->id];
                $daftarDiagnosa = $connection->createCommand($sql, $params)->queryAll();

                $sql = /** @lang SQL */ "
                    -- FILE: ".__FILE__." 
                    -- LINE: ".__LINE__." 
                    SELECT diag_tind
                    FROM db1.jadwaloperasi_rinc
                    WHERE
                        id_jadwaloperasi = :idJadwalOperasi
                        AND kode = 'T'
                ";
                $params = [":idJadwalOperasi" => $row->id];
                $daftarTindakan = $connection->createCommand($sql, $params)->queryAll();

                $sql = /** @lang SQL */ "
                    -- FILE: ".__FILE__." 
                    -- LINE: ".__LINE__." 
                    SELECT
                        nama   AS nama,
                        jumlah AS jumlah,
                        satuan AS satuan
                    FROM db1.alat_operasi
                    WHERE id_jadwal_operasi = :idJadwalOperasi
                ";
                $params = [":idJadwalOperasi" => $row->id];
                $daftarAlatOperasi = $connection->createCommand($sql, $params)->queryAll();

                $daftarHalaman[$h][$b] = [
                    "id" => $row->id,
                    "nama" => $row->nama,
                    "rencana_operasi" => $row->rencana_operasi,
                    "no_rm" => $row->no_rm,
                    "operator" => $row->operator,
                    "diagnosa" => $daftarDiagnosa ?: "-",
                    "tindakan" => $daftarTindakan ?: "-",
                    "alat" => $daftarAlatOperasi ?: "-",
                ];

                if ($b > $barisPerHalaman) {
                    $h++;
                    $b = 0;
                } else {
                    $b++;
                }
            }

            if (!$daftarHalaman) return "tidak ada data";

            $view = new LaporanDibuat(daftarHalaman: $daftarHalaman, jumlahHalaman: count($daftarHalaman));
            return $view->__toString();
        }
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/jadwaloperasi.php#detail    the original method
     */
    public function actionViewDetailData(): string
    {
        $id = Yii::$app->request->post("id") ?? throw new MissingPostParamException("id");
        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                JOP.id                         AS id,
                JOP.nama                       AS nama,
                JOP.no_rm                      AS kodeRekamMedis,
                JOP.umur                       AS umur,
                JOP.tanggal_lahir              AS tanggalLahir,
                JOP.jenis_kelamin              AS kelamin,
                JOP.no_telp                    AS noTelefon,
                JOP.gedung                     AS gedung,
                JOP.ruang                      AS ruang,
                JOP.kelas                      AS kelas,
                JOP.kelas_rm                   AS kelasRekamMedis,
                JOP.tempat_tidur               AS tempatTidur,
                JOP.tempat_tidur_id            AS idTempatTidur,
                JOP.cara_bayar                 AS caraBayar,
                JOP.jenis_cara_bayar           AS jenisCaraBayar,
                JOP.hubungan_keluarga_penjamin AS hubunganKeluargaPenjamin,
                JOP.no_peserta_jaminan         AS noPesertaJaminan,
                JOP.nama_peserta_jaminan       AS namaPesertaJaminan,
                JOP.asal_wilayah_jabotabek     AS asalWilayahJabotabek,
                JOP.asal_wilayah               AS asalWilayah,
                JOP.diagnosa                   AS diagnosa,
                JOP.tindakan                   AS tindakan,
                JOP.operator                   AS operator,
                JOP.id_dokter                  AS idDokter,
                JOP.dokter_bedah               AS dokterBedah,
                JOP.perawat_bedah              AS perawatBedah,
                JOP.dokter_anastesi            AS dokterAnastesi,
                JOP.penata_anastesi            AS penataAnastesi,
                JOP.rencana_operasi            AS rencanaOperasi,
                JOP.rencana_operasi_end        AS rencanaOperasiEnd,
                JOP.durasi_op                  AS durasiOperasi,
                JOP.smf                        AS smf,
                JOP.group_id                   AS idGroup,
                JOP.ruang_operasi              AS ruangOperasi,
                JOP.jenis_operasi              AS jenisOperasi,
                JOP.post_op                    AS pascaOperasi,
                JOP.status                     AS status,
                JOP.prioritas                  AS prioritas,
                JOP.infeksi                    AS infeksi,
                JOP.id_instalasi               AS idInstalasi,
                JOP.id_poli                    AS idPoli,
                JOP.request_akomodasi          AS requestAkomodasi,
                JOP.sysdate_in                 AS createdTime,
                JOP.sysdate_last               AS updatedTime,
                JOP.user_created               AS createdBy,
                JOP.user_last                  AS updatedBy,
                CBY.cara_bayar                 AS namaCaraBayar,
                JBY.nama                       AS namaJenisCaraBayar,
                ROP.nama                       AS namaRuangOperasi,
                INS.nama_instalasi             AS namaInstalasi,
                POL.nama_poli                  AS namaPoli
            FROM db1.jadwal_operasi AS JOP
            LEFT JOIN db1.master_cara_bayar AS CBY ON CBY.kd_bayar = JOP.cara_bayar
            LEFT JOIN db1.master_jenis_cara_bayar AS JBY ON JBY.id = JOP.jenis_cara_bayar
            LEFT JOIN db1.master_ruang_operasi AS ROP ON ROP.id = JOP.ruang_operasi
            LEFT JOIN db1.master_instalasi AS INS ON INS.id_instalasi = JOP.id_instalasi
            LEFT JOIN db1.master_poli AS POL ON POL.id_instalasi = JOP.id_instalasi
            WHERE
                JOP.id = :idJadwalOperasi
                AND POL.id_poli = JOP.id_poli
            LIMIT 1
        ";
        $params = [":idJadwalOperasi" => $id];
        $jadwalOperasi = $connection->createCommand($sql, $params)->queryOne();

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                id                AS id,
                id_jadwal_operasi AS idJadwalOperasi,
                nama              AS nama,
                jumlah            AS jumlah,
                satuan            AS satuan
            FROM db1.alat_operasi
            WHERE id_jadwal_operasi = :idJadwalOperasi
        ";
        $params = [":idJadwalOperasi" => $id];
        $daftarAlatOperasi = $connection->createCommand($sql, $params)->queryAll();

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                id               AS id,
                id_jadwaloperasi AS idJadwalOperasi,
                kode_diagtind    AS kodeDiagnosaTindakan,
                diag_tind        AS diagnosaTindakan,
                kode             AS kode,
                sysdate_in       AS createdTime,
                user_in          AS createdBy,
                sysdate_last     AS updatedTime,
                user_last        AS updatedBy
            FROM db1.jadwaloperasi_rinc
            WHERE
                id_jadwaloperasi = :idJadwalOperasi
                AND kode = 'D'
        ";
        $params = [":idJadwalOperasi" => $id];
        $daftarDiagnosaOperasi = $connection->createCommand($sql, $params)->queryAll();

        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                id               AS id,
                id_jadwaloperasi AS idJadwalOperasi,
                kode_diagtind    AS kodeDiagnosaTindakan,
                diag_tind        AS diagnosaTindakan,
                kode             AS kode,
                sysdate_in       AS createdTime,
                user_in          AS createdBy,
                sysdate_last     AS updatedTime,
                user_last        AS updatedBy
            FROM db1.jadwaloperasi_rinc
            WHERE
                id_jadwaloperasi = :idJadwalOperasi
                AND kode = 'T'
        ";
        $params = [":idJadwalOperasi" => $id];
        $daftarTindakanOperasi = $connection->createCommand($sql, $params)->queryAll();

        return json_encode([
            "jadwalOperasi" =>   $jadwalOperasi,
            "daftarDiagnosaOperasi" => $daftarDiagnosaOperasi,
            "daftarTindakanOperasi" => $daftarTindakanOperasi,
            "daftarAlatOperasi" =>     $daftarAlatOperasi,
        ]);
    }

    /**
     * @author Hendra Gunawan
     * @throws DateTimeException
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/jadwaloperasi.php#approved    the original method
     */
    public function actionSaveApprove(): string
    {
        [   "id" => $id,
            "status" => $status,
            "rencana_operasi" => $rencanaOperasi,
            "rencana_operasi_end" => $rencanaOperasiEnd,
        ] = Yii::$app->request->post();
        $status ??= 1;

        $toSystemPartialDatetime = Yii::$app->dateTime->transformFunc("toSystemPartialDatetime");
        $idUser = Yii::$app->userFatma->id;

        $data = [
            "id" => $id,
            "status" => $status,
            "user_last" => $idUser,
        ];
        if ($status == 1) {
            $data["ruang_operasi"] = Yii::$app->request->post("ruang_operasi");
            $data["rencana_operasi"] = $toSystemPartialDatetime(urldecode($rencanaOperasi));
            $data["rencana_operasi_end"] = $toSystemPartialDatetime(urldecode($rencanaOperasiEnd));
        }

        $retval = (new JadwalOperasiModel)->save($data);
        return json_encode(["return" => !!$retval]);
    }

    /**
     * @author Hendra Gunawan
     * @throws Exception
     * @see http://localhost/ori-source/fatma-pharmacy/controllers/jadwaloperasi.php#approved    the original method
     */
    public function actionApproveData(): string
    {
        $id = Yii::$app->request->post("id");
        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__." 
            -- LINE: ".__LINE__." 
            SELECT
                JOP.id                         AS id,
                JOP.nama                       AS nama,
                JOP.no_rm                      AS kodeRekamMedis,
                JOP.umur                       AS umur,
                JOP.tanggal_lahir              AS tanggalLahir,
                JOP.jenis_kelamin              AS kelamin,
                JOP.no_telp                    AS noTelefon,
                JOP.gedung                     AS gedung,
                JOP.ruang                      AS ruang,
                JOP.kelas                      AS kelas,
                JOP.kelas_rm                   AS kelasRekamMedis,
                JOP.tempat_tidur               AS tempatTidur,
                JOP.tempat_tidur_id            AS idTempatTidur,
                JOP.cara_bayar                 AS caraBayar,
                JOP.jenis_cara_bayar           AS jenisCaraBayar,
                JOP.hubungan_keluarga_penjamin AS hubunganKeluargaPenjamin,
                JOP.no_peserta_jaminan         AS noPesertaJaminan,
                JOP.nama_peserta_jaminan       AS namaPesertaJaminan,
                JOP.asal_wilayah_jabotabek     AS asalWilayahJabotabek,
                JOP.asal_wilayah               AS asalWilayah,
                JOP.diagnosa                   AS diagnosa,
                JOP.tindakan                   AS tindakan,
                JOP.operator                   AS operator,
                JOP.id_dokter                  AS idDokter,
                JOP.dokter_bedah               AS dokterBedah,
                JOP.perawat_bedah              AS perawatBedah,
                JOP.dokter_anastesi            AS dokterAnastesi,
                JOP.penata_anastesi            AS penataAnastesi,
                JOP.rencana_operasi            AS rencanaOperasi,
                JOP.rencana_operasi_end        AS rencanaOperasiEnd,
                JOP.durasi_op                  AS durasiOperasi,
                JOP.smf                        AS smf,
                JOP.group_id                   AS idGroup,
                JOP.ruang_operasi              AS ruangOperasi,
                JOP.jenis_operasi              AS jenisOperasi,
                JOP.post_op                    AS pascaOperasi,
                JOP.status                     AS status,
                JOP.prioritas                  AS prioritas,
                JOP.infeksi                    AS infeksi,
                JOP.id_instalasi               AS idInstalasi,
                JOP.id_poli                    AS idPoli,
                JOP.request_akomodasi          AS requestAkomodasi,
                JOP.sysdate_in                 AS createdTime,
                JOP.sysdate_last               AS updatedTime,
                JOP.user_created               AS createdBy,
                JOP.user_last                  AS updatedBy,
                ROP.nama                       AS namaRuangOperasi,
                INS.nama_instalasi             AS namaInstalasi,
                POL.nama_poli                  AS namaPoli
            FROM db1.jadwal_operasi AS JOP
            LEFT JOIN db1.master_ruang_operasi AS ROP ON ROP.id = JOP.ruang_operasi
            LEFT JOIN db1.master_instalasi AS INS ON INS.id_instalasi = JOP.id_instalasi
            LEFT JOIN db1.master_poli AS POL ON POL.id_instalasi = JOP.id_instalasi
            WHERE
                JOP.id = :idJadwalOperasi
                AND JOP.status = 0
                AND POL.id_poli = JOP.id_poli
            LIMIT 1
        ";
        $params = [":idJadwalOperasi" => $id];

        $jadwalOperasi = $connection->createCommand($sql, $params)->queryOne();
        $tanggalAwal = $jadwalOperasi->rencanaOperasi;
        $tanggalAkhir = $jadwalOperasi->rencanaOperasiEnd;

        return json_encode([
            "jadwalOperasi" => $jadwalOperasi,
            "ruangOperasi" => (new JadwalOperasiModel)->findRuangOperasi($tanggalAwal, $tanggalAkhir),
        ]);
    }
}
