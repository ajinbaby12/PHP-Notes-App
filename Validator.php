<?php

class Validator
{

    const test = 0;

    public function string($value, $min = 1, $max = INF)
    {
        $value = trim($value);

        return strlen($value) >= $min && strlen($value) <= $max;
    }
}

?>
