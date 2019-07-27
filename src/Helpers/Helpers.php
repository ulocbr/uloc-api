<?php
/**
 * Created by PhpStorm.
 * User: Tiago
 * Date: 23/03/2018
 * Time: 11:02
 */

namespace Uloc\ApiBundle\Helpers;


class Helpers
{

    public static function adjustName($fullName)
    {
        $nameParts = explode(' ', $fullName);
        $proname = array('da', 'de', 'di', 'do', 'dos');

        $newName = '';
        $key = 0;
        foreach ($nameParts as $name) {
            if (empty($name)) continue;
            $key++;
            $name = mb_strtolower(trim($name), 'UTF-8');
            $space = $key === 1 ? '' : ' ';
            if (in_array($name, $proname)) {
                $newName .= $space . $name;
            } else {
                $newName .= $space . ucfirst($name);
            }
        }

        return $newName;
    }

}