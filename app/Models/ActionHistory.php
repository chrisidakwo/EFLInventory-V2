<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\ActionHistory.
 *
 * @property int $id
 * @property int $user_id
 * @property string $description
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|ActionHistory newModelQuery()
 * @method static Builder|ActionHistory newQuery()
 * @method static Builder|ActionHistory query()
 * @method static Builder|ActionHistory whereCreatedAt($value)
 * @method static Builder|ActionHistory whereDescription($value)
 * @method static Builder|ActionHistory whereId($value)
 * @method static Builder|ActionHistory whereUpdatedAt($value)
 * @method static Builder|ActionHistory whereUserId($value)
 * @mixin Eloquent
 */
class ActionHistory extends Model {
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['description'];

    /**
     * @param $description
     * @return bool
     */
    public static function addNewActionHistory($description): bool {
        $history = new static();
        $history->user_id = auth()->id();
        $history->description = $description;

        return $history->save();
    }

//    public static function GetHistory($user_id): array {
//        return self::all()->where('user_id', $user_id)->all();
//    }

    // No need to add an update or delete function.
    // Why store the history of users' actions if only to modify or delete them later?
}
