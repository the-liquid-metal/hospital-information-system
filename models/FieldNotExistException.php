<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\models;

use Exception;

/**
 * @copyright  PT Affordable App Jl Mampang Prapatan VI no. 15B, Tegal Parang, Mampang, Jakarta Selatan, Jakarta, Indonesia
 * @license    Affordable App License
 * @author     Hendra Gunawan <the.liquid.metal@gmail.com>
 * @version    1.0
 * @since      1.0
 * @category   application
 */
class FieldNotExistException extends Exception
{
    /**
     * @author Hendra Gunawan
     * @param $fieldName
     */
    public function __construct($fieldName)
    {
        parent::__construct("There is no field with name: $fieldName", 0, null);
    }
}
