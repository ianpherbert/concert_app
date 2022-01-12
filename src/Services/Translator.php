<?php

namespace App\Services;

use Error;
use Exception;
use TypeError;


class Translator
{
    public function getMonth(int $month): string
    {
        switch ($month) {
            case 1:
                return "janvier";
                break;
            case 2:
                return "fevrier";
                break;
            case 3:
                return "mars";
                break;
            case 4:
                return "avril";
                break;
            case 5:
                return "mai";
                break;
            case 6:
                return "juin";
                break;
            case 7:
                return "juillet";
                break;
            case 8:
                return "août";
                break;
            case 9:
                return "septembre";
                break;
            case 10:
                return "octobre";
                break;
            case 11:
                return "novembre";
                break;
            case 12:
                return "decembre";
                break;
            default:
                return null;
        }
    }
}
