<?php

namespace App;

class Acl
{
    private static $allow = [];
    private static $deny = [];

    public static function init()
    {
        self::$allow = [];
        self::$deny = [];
    }

    public static function allow($role, $actions=[])
    {
        self::$allow[$role] = $actions;
    }

    public static function isAllowed($role, $action) {

        $rules = self::$allow;

        if(!array_key_exists($role, $rules))
        {
            return false;
        }

        if(count($rules[$role]) == 0) {
            self::reset();
            return true;
        }
        foreach($rules[$role] as $rule) {
            if($rule == $action) {
                self::reset();
                return true;
            }
        }
        self::reset();
        return false;
    }

    public static function deny($role, $actions = [])
    {
        self::$deny[$role] = $actions;
    }

    public static function isNotAllowed($role) {

        $rules = self::$deny;

        if(array_key_exists($role, $rules))
        {
            self::reset();
            return false;
        }
        return true;
    }

    private static function reset() {
        self::$allow = [];
    }
}