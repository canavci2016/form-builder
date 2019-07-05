<?php

function array_dot($array, $prepend = '')
{
    $results = [];

    foreach ($array as $key => $value) {
        if (is_array($value) && !empty($value)) {
            $results = array_merge($results, array_dot($value, $prepend . $key . '.'));
        } else {
            $results[$prepend . $key] = $value;
        }
    }

    return $results;
}