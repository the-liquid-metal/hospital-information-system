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
 *
 * @property \CI_Driver|\CI_DB_driver|\CI_DB_mysql_driver|\CI_DB_active_record db
 * @property \CI_Session session
 * @property \CI_Session url
 * @property \CI_Session form
 * @property \CI_Session text_helper
 * @property object load
 */
class BaseModel extends \CI_Model
{
    /**
     * @author Hendra Gunawan
     */
    protected string $tableName;

    /**
     * @author Hendra Gunawan
     */
    protected string $primaryKey = "id";

    /**
     * @author Hendra Gunawan
     */
    protected string $resultMode = "array";

    /**
     * @author Hendra Gunawan
     */
    public function __construct()
    {
        parent::__construct();
        $this->tableName ??= $this::class;
        $this->load->database();
    }

    /**
     * TODO: sql: refactor: move logic to controller
     * @author Hendra Gunawan
     */
    public function save(array $data): mixed
    {
        $fields = $this->db->list_fields($this->tableName);
        foreach ($fields as $field) {
            if (isset($data[$field])) {
                $this->db->set($field, $data[$field]);
            }
        }
        if (! isset($data['id'])) {
            $return = $this->db->insert($this->tableName);
        } else {

            if (is_array($this->primaryKey)) {
                $ids = explode(".", $data['id']);
                foreach ($this->primaryKey as $idx => $key) {
                    $this->db->where("{$this->tableName}.{$key}", $ids[$idx]);
                }
            } else {
                $this->db->where("{$this->tableName}.{$this->primaryKey}", $data['id']);
            }

            $return = $this->db->update($this->tableName);
        }

        return $return;
    }
}
