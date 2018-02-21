<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Action;

class ActionHistory extends Model {

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ["description"];

    public static function AddNewActionHistory($description) {
        $history = new ActionHistory();
        $history->user_id = \Auth::user()->id;
        $history->description = $description;
        $history->save();
    }

    public static function GetHistory($user_id) {
        $history = ActionHistory::all()->where("user_id", $user_id)->all();
        return $history;
    }

    // No need to add an update or delete function.
    // Why store the history of users' actions if only to modify or delete them later?
}
