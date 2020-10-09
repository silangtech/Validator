<?php
/*LICENSE
+-----------------------------------------------------------------------+
| SilangPHP Framework                                                   |
+-----------------------------------------------------------------------+
| This program is free software; you can redistribute it and/or modify  |
| it under the terms of the GNU General Public License as published by  |
| the Free Software Foundation. You should have received a copy of the  |
| GNU General Public License along with this program.  If not, see      |
| http://www.gnu.org/licenses/.                                         |
| Copyright (C) 2020. All Rights Reserved.                              |
+-----------------------------------------------------------------------+
| Supports: http://www.github.com/silangtech/SilangPHP                  |
+-----------------------------------------------------------------------+
*/
namespace SilangPHP\Validator;
/**
 * 数据验证
 * Class Validator
 * @package SilangPHP\Validator
 *   [
 *   'username' => 'required|min:10',
 *   'password' => 'required|min:6|max:20'
 *   ];
 */
class Validator
{
    // 验证的规则
    public static $rules = [];
    public static $error = 0;
    public static $errMsg = "";

    /**
     * 简单验证
     * @param $data
     * @param $rules
     * @return bool
     */
    public static function check($data,$rules = [])
    {
        self::$error = 0;
        if($rules)
        {
            foreach($rules as $field => $rulevalue)
            {
                $rule = explode("|",$rulevalue);
                $fieldval = $data[$field];
                foreach($rule as $validrule)
                {
                    $validrule = explode(":",$validrule);
                    if(isset($validrule['1']))
                    {
                        $valid = call_user_func_array(array(\SilangPHP\Validator\Validator, $validrule['0']), array($fieldval,$validrule['1']));
                    }else{
                        $valid = call_user_func_array(array(\SilangPHP\Validator\Validator, $validrule['0']), array($fieldval));
                    }
                    if($valid === false)
                    {
                        self::$error = -1;
                        self::$errMsg = $field."|".$validrule['0']."error";
                        return false;
                    }
                }
            }
        }
        return true;
    }

    public static function min($val,$len = 10)
    {
        if(strlen<$len)
        {
            return false;
        }
        return $val;
    }

    public static function max($val,$len)
    {
        if(strlen>$len)
        {
            return false;
        }
        return $val;
    }

    public static function email($email){
        return filter_var($email, FILTER_SANITIZE_EMAIL);
    }

    public static function boolean($val)
    {
//        return filter_var($val, FILTER_VALIDATE_BOOLEAN);
        return is_bool($val) ? $val : false;
    }

    public static function ip($ip)
    {
        return filter_var($ip, FILTER_VALIDATE_IP);
    }

    public static function numeric($val){
        return preg_replace('/\D/', '', $val);
    }

    public static function integer($val){
        return filter_var($val, FILTER_VALIDATE_INT);
    }

    public static function float($val){
        return filter_var($val, FILTER_VALIDATE_FLOAT);
    }

    public static function url($val){
        $val = strip_tags(str_replace(array('"', "'", '`', '´', '¨'), '', trim($val)));
        return filter_var($val, FILTER_SANITIZE_URL);
    }

    public static function json($val, $strict = true): bool
    {
        if (!$val || (!is_string($val) && !method_exists($val, '__toString'))) {
            return false;
        }
        $val = (string)$val;
        if ($strict && '[' !== $val[0] && '{' !== $val[0]) {
            return false;
        }
        json_decode($val, true);
        return json_last_error() === JSON_ERROR_NONE;
    }

    public static function required($val)
    {
        return $val === '' || $val === null || $val === [];
    }

    public static function date($val)
    {
        return strtotime((string)$val) ? $val : false;
    }

    public static function name($name): bool
    {
        if (!$name || !is_string($name)) {
            return false;
        }
        return 1 === preg_match('/^[a-zA-Z0-9_.-]+$/', $name);
    }

}