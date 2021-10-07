<?php

namespace App\Utils;

trait HydrateTrait
{
    public function hydrate($data)
    {
        foreach ($data as $attribute => $value) {
            $method = 'set'.str_replace(' ', '', ucwords(str_replace('_', ' ', $attribute)));
            if (is_callable([$this, $method])) {
                $this->$method($value);
            }
        }
    }
}
