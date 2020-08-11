<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\ErrorLog.
 *
 * @property int $id
 * @property string $active_user
 * @property string $inner_exception
 * @property string $error_message
 * @property string $stack_trace
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|ErrorLog newModelQuery()
 * @method static Builder|ErrorLog newQuery()
 * @method static Builder|ErrorLog query()
 * @method static Builder|ErrorLog whereActiveUser($value)
 * @method static Builder|ErrorLog whereCreatedAt($value)
 * @method static Builder|ErrorLog whereErrorMessage($value)
 * @method static Builder|ErrorLog whereId($value)
 * @method static Builder|ErrorLog whereInnerException($value)
 * @method static Builder|ErrorLog whereStackTrace($value)
 * @method static Builder|ErrorLog whereUpdatedAt($value)
 * @mixin Eloquent
 */
class ErrorLog extends Model {
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['inner_exception', 'error_message', 'stack_trace'];

    public static function AddNewError($inner_exception, $error_message, $stack_trace) {
        self::create([
            'active_user' => auth()->user()->username,
            'inner_exception' => $inner_exception,
            'error_message' => $error_message,
            'stack_trace' => $stack_trace
        ]);
    }
}
