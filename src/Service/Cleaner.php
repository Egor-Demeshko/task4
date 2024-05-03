<?php

namespace App\Service;

use App\Exception\ClearError;

class Cleaner
{
    public function __construct(private ClearError $error)
    {
    }
    public function clearString(string $data)
    {
        return strip_tags($data);
    }

    /**
     * @throws ClearError
     */
    public function insureIntInArray(array $data)
    {
        foreach ($data as $value) {
            $number = (int) $value;
            if ($number === 0 || is_nan($number)) {
                throw new $this->error;
            }
        }
    }
}
