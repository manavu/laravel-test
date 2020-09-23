<?php

namespace App\Traits;

use Illuminate\Support\Str;

/**
 * Trait CamelCaseAccessible.
 *
 * @mixin \Illuminate\Database\Eloquent\Builder
 */

/**
 * CamelCaseAccessible trait
 */
trait  CamelCaseAccessible
{
    function getAttribute($key)
    {
        return parent::getAttribute(Str::snake($key));
    }

    function setAttribute($key, $value)
    {
        return parent::setAttribute(Str::snake($key), $value);
    }
}
