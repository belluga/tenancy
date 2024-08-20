<?php

declare(strict_types=1);

namespace Belluga\Tenancy\Database\Models;

use Carbon\Carbon;
use MongoDB\Laravel\Eloquent\Model;
use Illuminate\Support\Str;
use Belluga\Tenancy\Database\Concerns\CentralConnection;

/**
 * @param string $token
 * @param string $tenant_id
 * @param string $user_id
 * @param string $auth_guard
 * @param string $redirect_url
 * @param Carbon $created_at
 */
class ImpersonationToken extends Model
{
    use CentralConnection;

    protected $guarded = [];
    public $timestamps = false;
    protected $primaryKey = 'token';
    public $incrementing = false;
    protected $table = 'tenant_user_impersonation_tokens';
    protected $casts = [
        'created_at' => 'datetime',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_at = $model->created_at ?? $model->freshTimestamp();
            $model->token = $model->token ?? Str::random(128);
            $model->auth_guard = $model->auth_guard ?? config('auth.defaults.guard');
        });
    }
}
