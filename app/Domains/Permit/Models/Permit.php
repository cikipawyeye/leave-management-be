<?php

declare(strict_types=1);

namespace App\Domains\Permit\Models;

use App\Domains\Permit\State\Permit\Approved;
use App\Domains\Permit\State\Permit\PermitState;
use App\Domains\Permit\State\Permit\Rejected;
use App\Domains\User\Models\User;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\ModelStates\HasStates;

class Permit extends Model
{
    /** @use HasFactory<\Database\Factories\PermitFactory> */
    use HasFactory, HasStates, SoftDeletes;

    protected $casts = [
        'state' => PermitState::class,
    ];

    protected $fillable = [
        'user_id',
        'type',
        'title',
        'content',
        'state',
    ];

    protected static function newFactory(): Factory
    {
        return \Database\Factories\PermitFactory::new();
    }

    /**
     * Interact with the stateLabel attribute.
     */
    public function stateLabel(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->state?->stateLabel()
        );
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isProcessed(): bool
    {
        return $this->state instanceof Approved || $this->state instanceof Rejected;
    }
}
