<?php
function arrayGet($array, $keySearch, $default = null) {
    $keySearch = explode(".", $keySearch);
    $currentResult = $default;
    foreach($keySearch as $key) {
        if (array_key_exists($key, $array)) {
            $currentResult = $array[$key];
            if (is_array($currentResult))  {
                array_shift($keySearch);
                $currentResult = arrayGet($currentResult, implode(".", $keySearch));
            }
        }
    }
    return $currentResult;
}
