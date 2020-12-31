<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\models;

/**
 * @copyright  PT Affordable App Jl Mampang Prapatan VI no. 15B, Tegal Parang, Mampang, Jakarta Selatan, Jakarta, Indonesia
 * @license    Affordable App License
 * @author     Hendra Gunawan <the.liquid.metal@gmail.com>
 * @version    1.0
 * @since      1.0
 * @category   application
 */
class FarmasiModel2 extends BaseModel
{

    /**
     * @author Hendra Gunawan
     */
    public function __construct() {
        parent::__construct();
        $this->tableName = "masterf_katalog";
        $this->primaryKey = "kode";
        $this->resultMode = "object";
    }

    /**
     * TODO: sql: refactor: move logic to controller
     * @author Hendra Gunawan
     */
    public function saveData(mixed $tbName, mixed $fieldList, mixed $data, mixed $where = null): object|bool
    {
        if ($where != null) {
            $this->db->where($where);
            foreach ($fieldList as $val) {
                $this->db->set($val, $data[$val]);
            }
            return $this->db->update($tbName);

        } else {
            foreach ($fieldList as $val) {
                $this->db->set($val, $data[$val]);
            }
            return $this->db->insert($tbName);
        }
    }

    /**
     * TODO: sql: refactor: move logic to controller
     * @author Hendra Gunawan
     */
    public function insertData(mixed $tbName, mixed $data): object|bool
    {
        return $this->db->insert($tbName, $data);
    }

    /**
     * TODO: sql: refactor: move logic to controller
     * @author Hendra Gunawan
     */
    public function saveBatch(mixed $tbName, mixed $data, mixed $where = null): object|bool|int
    {
        if ($where != null) {
            $this->db->delete($tbName, $where);
        }
        return $this->db->insert_batch($tbName, $data);
    }

    /**
     * TODO: sql: refactor: move logic to controller
     * @author Hendra Gunawan
     */
    public function saveInsertid(mixed $tableName, mixed $fieldList, mixed $data): int
    {
        foreach ($fieldList as $val) {
            $this->db->set($val, $data[$val]);
        }

        $this->db->trans_start();
        $this->db->insert($tableName);
        $id = $this->db->insert_id();
        $this->db->trans_complete();
        return $id;
    }
}
