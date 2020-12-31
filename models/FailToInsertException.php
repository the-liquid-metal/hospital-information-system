<?php
declare(strict_types=1);

namespace tlm\his\FatmaPharmacy\models;

use Exception;
use Yii\db\Transaction;


/**
 * @copyright  PT Affordable App Jl Mampang Prapatan VI no. 15B, Tegal Parang, Mampang, Jakarta Selatan, Jakarta, Indonesia
 * @license    Affordable App License
 * @author     Hendra Gunawan <the.liquid.metal@gmail.com>
 * @version    1.0
 * @since      1.0
 * @category   application
 */
class FailToInsertException extends Exception
{
    /**
     * @author Hendra Gunawan
     * @param string $tableAlias
     * @param Transaction|null $transaction
     * @throws \yii\db\Exception
     */
    public function __construct(string $tableAlias, Transaction $transaction = null)
    {
        $transaction && $transaction->rollBack();
        parent::__construct("Fail insert data into '$tableAlias''");
    }
}
