<?php

/**
 * SPDX-License-Identifier: MIT
 * (c) 2025 GegoSoft Technologies and GegoK12 Contributors
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PayrollTransaction
 *
 * Model for managing payroll transactions and payment records.
 *
 * @property int $id
 * @property int $school_id
 * @property string $transaction_no
 * @property int $paytype_id
 * @property int $account_id
 * @property int $staff_id
 * @property int $payroll_id
 * @property \DateTime $transaction_date
 * @property decimal $amount
 * @property string $payment_method
 * @property array|null $transaction_detail
 * @property string|null $reference_number
 * @property string|null $attachment
 * @property string|null $remarks
 * @property \DateTime $created_at
 * @property \DateTime $updated_at
 * @property \DateTime $deleted_at
 * @property-read User $user
 * @property-read TransactionType $transactiontype
 * @property-read Payroll $payroll
 * @property-read TransactionAccount $account
 *
 * @mixin \Eloquent
 */
class PayrollTransaction extends Model
{
    //
    use SoftDeletes;

    protected $casts = ['transaction_detail' => 'array'];

    protected $fillable = [
        'school_id', 'transaction_no', 'paytype_id', 'account_id', 'staff_id', 'payroll_id', 'transaction_date', 'amount', 'payment_method', 'transaction_detail', 'reference_number', 'attachment', 'remarks',
    ];

    /**
     * Get the staff member for this transaction.
     *
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'staff_id');
    }

    /**
     * Get the transaction type for this transaction.
     *
     * @return BelongsTo
     */
    public function transactiontype()
    {
        return $this->belongsTo(TransactionType::class, 'paytype_id');
    }

    /**
     * Get the payroll record for this transaction.
     *
     * @return BelongsTo
     */
    public function payroll()
    {
        return $this->belongsTo(Payroll::class, 'payroll_id');
    }

    /**
     * Get the transaction account for this transaction.
     *
     * @return BelongsTo
     */
    public function account()
    {
        return $this->belongsTo(TransactionAccount::class, 'account_id');
    }
}
