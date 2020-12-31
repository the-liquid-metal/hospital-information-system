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
class DataNotExistException extends Exception
{
    /**
     * @author Hendra Gunawan
     * @param $id
     */
    public function __construct(...$id)
    {
        parent::__construct("There is no data with ID: $id", 0, null);
    }
}
