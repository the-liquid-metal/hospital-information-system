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
class TimeOutOfRangeException extends Exception
{
    /**
     * @author Hendra Gunawan
     */
    public function __construct()
    {
        parent::__construct("The value is out of range", 0, null);
    }
}
