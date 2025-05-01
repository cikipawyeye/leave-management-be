<?php

declare(strict_types=1);

namespace App\Domains\Permit\Models;

use App\Domains\Permit\State\PermitReview\PermitReviewState;
use App\Domains\User\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\ModelStates\HasStates;

class PermitReview extends Model
{
    /** @use HasFactory<\Database\Factories\PermitReviewFactory> */
    use HasFactory, HasStates, SoftDeletes;

    protected $casts = [
        'state' => PermitReviewState::class,
    ];

    protected $fillable = [
        'permit_id',
        'reviewer_id',
        'state',
        'comment',
    ];

    protected static function newFactory(): Factory
    {
        return \Database\Factories\PermitReviewFactory::new();
    }

    public function permit(): BelongsTo
    {
        return $this->belongsTo(Permit::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }
}
