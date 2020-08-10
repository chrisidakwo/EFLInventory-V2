<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\BackupConfig.
 *
 * @property int $id
 * @property string $account
 * @property string $password
 * @property string $account_key
 * @property string $folder_name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|BackupConfig newModelQuery()
 * @method static Builder|BackupConfig newQuery()
 * @method static Builder|BackupConfig query()
 * @method static Builder|BackupConfig whereAccount($value)
 * @method static Builder|BackupConfig whereAccountKey($value)
 * @method static Builder|BackupConfig whereCreatedAt($value)
 * @method static Builder|BackupConfig whereFolderName($value)
 * @method static Builder|BackupConfig whereId($value)
 * @method static Builder|BackupConfig wherePassword($value)
 * @method static Builder|BackupConfig whereUpdatedAt($value)
 * @mixin Eloquent
 */
class BackupConfig extends Model {
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['account', 'password', 'account_key', 'folder_name'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = ['password', 'key'];
}
