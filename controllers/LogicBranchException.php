<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\controllers;

use Exception;

/**
 * @copyright  PT Affordable App Jl Mampang Prapatan VI no. 15B, Tegal Parang, Mampang, Jakarta Selatan, Jakarta, Indonesia
 * @license    Affordable App License
 * @author     Hendra Gunawan <the.liquid.metal@gmail.com>
 * @version    1.0
 * @since      1.0
 * @category   application
 */
class LogicBranchException extends Exception
{
    /**
     * @author Hendra Gunawan
     */
    public function __construct()
    {
        parent::__construct("The logic flow entering a branch which is not anticipated to produce output");
    }
}
