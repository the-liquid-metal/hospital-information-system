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
class GroupModel extends BaseModel
{
    /**
     * @author Hendra Gunawan
     */
    public function __construct() {
        parent::__construct();
        $this->tableName = "group";
        $this->resultMode = "object";
    }

    /**
     * TODO: sql: refactor: move logic to controller
     * @author Hendra Gunawan
     */
    public function saveModule(array $data): object|null|bool
    {
        $this->db->where('group_id', $data['id']);
        $this->db->delete('group_module');

        $return = null;
        if (is_array($data['module'])) {
            foreach ($data['module'] as $module) {
                $this->db->set('group_id', $data['id']);
                $this->db->set('module_id', $module);
                if (!empty($data['permission'][$module]) && is_array($data['permission'][$module])) {
                    $this->db->set('permission', implode(',', $data['permission'][$module]));
                }
                $return = $this->db->insert('group_module');
            }
        }
        return $return;
    }

    /**
     * TODO: sql: refactor: move logic to controller
     * @author Hendra Gunawan
     */
    public function findModule(mixed $group = 0): mixed
    {
        $this->db->select("module.id, module.name, module.description, module.controller, module.action, permission, group.id as group_id");
        if (is_array($group)) {
            $this->db->join('group_module', 'group.id = group_id AND group_id IN (' . implode(',', $group) . ')', 'left');
        } else {
            $this->db->join('group_module', 'group.id = group_id and group_id = ' . $group, 'left');
        }
        $this->db->join('module', 'module_id = module.id', 'right');

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
}
