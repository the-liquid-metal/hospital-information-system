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
class AlatOperasiModel extends BaseModel
{
    /**
     * @author Hendra Gunawan
     */
    public function __construct()
    {
        parent::__construct();
        $this->tableName = "alat_operasi";
        $this->primaryKey = "id";
        $this->resultMode = "object";
    }

    /**
     * TODO: sql: refactor: move logic to controller
     * @author Hendra Gunawan
     */
    public function clear(int $id): void
    {
        $this->db->where("id_jadwal_operasi", $id);
        $this->db->delete($this->tableName);
    }
}
