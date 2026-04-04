<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    /**
     * Fields that can be mass assigned via create() or update().
     */
    protected $fillable = [
        'user_id',
        'type',
        'amount',
        'description',
        'category',
        'transaction_date',
    ];

    /**
     * Cast fields to proper PHP types automatically.
     */
    protected $casts = [
        'amount' => 'decimal:2',
        'transaction_date' => 'date',
    ];

    // ─── Relationships ────────────────────────────────────────────

    /**
     * Each transaction belongs to one user.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ─── Query Scopes ─────────────────────────────────────────────

    /**
     * Scope: filter by type (income or expense).
     * Usage: Transaction::ofType('income')->get()
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope: filter transactions within a date range.
     * Usage: Transaction::inDateRange('2024-01-01', '2024-12-31')->get()
     */
    public function scopeInDateRange($query, ?string $from, ?string $to)
    {
        if ($from) {
            $query->where('transaction_date', '>=', $from);
        }
        if ($to) {
            $query->where('transaction_date', '<=', $to);
        }
        return $query;
    }
}