<?php

namespace EFLInventory\Entities;


use Illuminate\Database\Eloquent\Model;

class HasStringID extends Model {
    /**
     * @var string
     */
    protected $primaryKey = "id";

    /**
     * @var string
     */
    protected $keyType = "string";

    /**
     * @var bool
     */
    public $incrementing = false;

    /**
     * @var array
     */
    protected $visible = ["id"];
}
