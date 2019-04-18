<?php

namespace EFLInventory\Traits;

trait UsesStringId {
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
}
