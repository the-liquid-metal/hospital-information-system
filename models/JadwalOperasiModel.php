<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\models;

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
class JadwalOperasiModel extends BaseModel
{

    /**
     * @author Hendra Gunawan
     */
    public function __construct() {
        parent::__construct();
        $this->tableName = "jadwal_operasi";
        $this->resultMode = "object";
    }

    /**
     * TODO: sql: refactor: move logic to controller
     * @author Hendra Gunawan
     */
    public function ubah(array $datainput): array|int
    {
        if ($datainput['status'] == 2) {
            $data = [
                'operator' => $datainput['operator'],
                'id_dokter' => $datainput['id_dokter'],
                'request_akomodasi' => $datainput['request_akomodasi'],
                'post_op' => $datainput['post_op'],
                'jenis_operasi' => $datainput['jenis_operasi'],
                'prioritas' => $datainput['prioritas'],
                'infeksi' => $datainput['infeksi'],
                'rencana_operasi' => $datainput['rencana_operasi'],
                'rencana_operasi_end' => $datainput['rencana_operasi_end'],
                'user_last' => $datainput['user_last'],
                'status' => '0'
            ];
        } else {
            $data = [
                'operator' => $datainput['operator'],
                'id_dokter' => $datainput['id_dokter'],
                'request_akomodasi' => $datainput['request_akomodasi'],
                'post_op' => $datainput['post_op'],
                'jenis_operasi' => $datainput['jenis_operasi'],
                'prioritas' => $datainput['prioritas'],
                'infeksi' => $datainput['infeksi'],
                'rencana_operasi' => $datainput['rencana_operasi'],
                'rencana_operasi_end' => $datainput['rencana_operasi_end'],
                'user_last' => $datainput['user_last']
            ];
        }
        $this->db->where('id', $datainput['id']);
        $this->db->update($this->tableName, $data);

        return $this->db->affected_rows();
    }

    /**
     * TODO: sql: refactor: move logic to controller
     * @author Hendra Gunawan
     * @param null $filters
     * @return null
     */
    public function findJadwalReschedule($filters = null)
    {
        if ($this->primaryKey != 'id') {
            $this->db->select("{$this->primaryKey} as id");
        }

        $this->db->select("
            *,
            DAYOFWEEK(rencana_operasi) AS hari,
            DAYOFWEEK(rencana_operasi_end) AS hari_end,
            TIME(rencana_operasi) AS jam,
            TIME(rencana_operasi_end) AS jam_end
        ");

        if ($filters) {
            $this->db->where($filters);
        }

        $this->db->order_by('hari, jam');

        $query = $this->db->get($this->tableName);
        if ($query->num_rows() > 0) {
            if ($this->resultMode === 'object') {
                return $query->result();
            } else {
                return $query->result_array();
            }
        } else {
            return null;
        }
    }

    /**
     * TODO: sql: refactor: move logic to controller
     * @author Hendra Gunawan
     * @throws Exception
     */
    public function findJadwal(string $filters = ""): array
    {
        if ($this->primaryKey != 'id') {
            $this->db->select("{$this->primaryKey} AS id");
        }

        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__."
            -- LINE: ".__LINE__. "
            SELECT
                a.id                           AS id,
                a.nama                         AS nama,
                a.no_rm                        AS kodeRekamMedis,
                a.umur                         AS umur,
                a.tanggal_lahir                AS tanggalLahir,
                a.jenis_kelamin                AS kelamin,
                a.no_telp                      AS noTelefon,
                a.gedung                       AS gedung,
                a.ruang                        AS ruang,
                a.kelas                        AS kelas,
                a.kelas_rm                     AS kelasRekamMedis,
                a.tempat_tidur                 AS tempatTidur,
                a.tempat_tidur_id              AS tempatTidurId,
                a.cara_bayar                   AS caraBayar,
                a.jenis_cara_bayar             AS jenisCaraBayar,
                a.hubungan_keluarga_penjamin   AS hubunganKeluargaPenjamin,
                a.no_peserta_jaminan           AS noPesertaJaminan,
                a.nama_peserta_jaminan         AS namaPesertaJaminan,
                a.asal_wilayah_jabotabek       AS asalWilayahJabotabek,
                a.asal_wilayah                 AS asalWilayah,
                a.diagnosa                     AS diagnosa,
                a.tindakan                     AS tindakan,
                a.operator                     AS operator,
                a.id_dokter                    AS idDokter,
                a.dokter_bedah                 AS dokterBedah,
                a.perawat_bedah                AS perawatBedah,
                a.dokter_anastesi              AS dokterAnastesi,
                a.penata_anastesi              AS penataAnastesi,
                a.rencana_operasi              AS rencanaOperasi,
                a.rencana_operasi_end          AS rencanaOperasiEnd,
                a.durasi_op                    AS durasiOperasi,
                a.smf                          AS smf,
                a.group_id                     AS groupId,
                a.ruang_operasi                AS ruangOperasi,
                a.jenis_operasi                AS jenisOperasi,
                a.post_op                      AS postOperasi,
                a.status                       AS status,
                a.prioritas                    AS prioritas,
                a.infeksi                      AS infeksi,
                a.id_instalasi                 AS idInstalasi,
                a.id_poli                      AS idPoli,
                a.request_akomodasi            AS requestAkomodasi,
                a.sysdate_in                   AS inputTime,
                a.sysdate_last                 AS updateTime,
                a.user_created                 AS inputById,
                a.user_last                    AS updatedById,
                DAYOFWEEK(rencana_operasi)     AS hari,
                DAYOFWEEK(rencana_operasi_end) AS hariEnd,
                TIME(rencana_operasi)          AS jam,
                TIME(rencana_operasi_end)      AS jamEnd,
                aa.diag_tind                   AS diag,
                bb.diag_tind                   AS tind
            FROM rsupf.jadwal_operasi AS a
            LEFT JOIN (
                SELECT
                    diag_tind,
                    id_jadwaloperasi
                FROM rsupf.jadwaloperasi_rinc AS a
                JOIN rsupf.jadwal_operasi AS b ON b.id = a.id_jadwaloperasi
                WHERE a.kode = 'D'
            ) AS aa ON a.id = aa.id_jadwaloperasi
            LEFT JOIN (
                SELECT
                    diag_tind,
                    id_jadwaloperasi
                FROM rsupf.jadwaloperasi_rinc AS a
                JOIN rsupf.jadwal_operasi AS b ON b.id = a.id_jadwaloperasi
                WHERE a.kode = 'T'
            ) AS bb ON a.id = bb.id_jadwaloperasi
            WHERE $filters
            ORDER BY hari, jam
        ";
        return $connection->createCommand($sql)->queryAll();
    }

    /**
     * TODO: sql: refactor: move logic to controller
     * @author Hendra Gunawan
     * @throws Exception
     */
    public function findRuangOperasi(string $tgl, string $tgl_end): array
    {
        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__."
            -- LINE: ".__LINE__."
            SELECT *
            FROM rsupf.master_ruang_operasi
            WHERE id NOT IN (
                SELECT ruang_operasi
                FROM rsupf.jadwal_operasi
                WHERE
                    rencana_operasi >= '$tgl'
                    AND rencana_operasi_end <= '$tgl_end'
                    AND ruang_operasi IS NOT NULL
            )
        ";
        return $connection->createCommand($sql)->queryAll();
    }

    /**
     * TODO: sql: refactor: move logic to controller
     * @author Hendra Gunawan
     * @throws Exception
     */
    public function cariLaporan(array $data = []): array
    {
        $connection = Yii::$app->dbFatma;
        $sql = /** @lang SQL */ "
            -- FILE: ".__FILE__."
            -- LINE: ".__LINE__."
            SELECT
                id,
                nama,
                no_rm,
                ruang,
                rencana_operasi
            FROM rsupf.jadwal_operasi
            WHERE
                status = 1
                AND operator = '{$data["dokter"]}'
                AND rencana_operasi >= '{$data["tanggal_awal"]}'
                AND rencana_operasi_end <= '{$data["tanggal_akhir"]}'
            ORDER BY rencana_operasi
        ";
        return $connection->createCommand($sql)->queryAll();
    }

    /**
     * TODO: sql: refactor: move logic to controller
     * @author Hendra Gunawan
     * @throws Exception
     */
    public function cariLaporan2(array $data = []): array|null
    {
        if ($data['dokter'] != '') {
            $connection = Yii::$app->dbFatma;
            $sql = /** @lang SQL */ "
                -- FILE: ".__FILE__."
                -- LINE: ".__LINE__."
                SELECT
                    id,
                    nama,
                    no_rm,
                    rencana_operasi,
                    operator
                FROM rsupf.jadwal_operasi
                WHERE
                    status = 0
                    AND rencana_operasi >= '{$data["tanggal_awal"]}'
                    AND rencana_operasi_end <= '{$data["tanggal_akhir"]}'
                ORDER BY rencana_operasi
            ";
            return $connection->createCommand($sql)->queryAll();

        } else {
            return null;
        }
    }
}
