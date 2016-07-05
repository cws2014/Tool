<?php
/**
* @file Array.php
* @synopsis  数组数据处理
* @author chenwensi
* @version 1
* @date 2016-07-05
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class CI_Array {

    public function getFieldValue($arrData, $strKey = 'id') {
        $arrValue = array();
        if(!is_array($arrData) || !count($arrData)) {
            return $arrValue;
        }
        foreach($arrData as $key => $val) {
            if(isset($val[$strKey])) {
                $arrValue[] = $val[$strKey];
            }
        }
        $arrValue = array_unique($arrValue);
        return $arrValue;
    }

    public function getMapFromList($arrData, $strKey = 'id') {
        $arrValue = array();
        if(!is_array($arrData) || !count($arrData)) {
            return $arrValue;
        }
        foreach($arrData as $val) {
            if(isset($val[$strKey])) {
                $arrValue[$val[$strKey]] = $val;
            }
        }
        return $arrValue;
    }

    public function getFieldArray($arrData, $strKey = 'id') {
        $arrValue = array();
        if(!is_array($arrData) || !count($arrData)) {
            return $arrValue;
        }
        foreach($arrData as $val) {
            if(!isset($val[$strKey])) {
                continue;
            }
            $key = $val[$strKey];
            if(isset($arrValue[$key])) {
                $arrValue[$key][] = $val;
            } else {
                $arrValue[$key] = array($val);
            }
        }
        return $arrValue;
    }


    /**
        * @synopsis  sort2d 二维数组按键值排序
        *
        * @param $arrData 要排序的数组
        * @param $strKey 排序的键
        * @param $strOrder 排序的顺序 默认asc为升序
        *
        * @returns   
     */
    public function sort2d($arrData, $strKey, $strOrder='asc') {
        $strOrder = strtolower($strOrder);
        $funcSort = function($arrItem1, $arrItem2) use($strKey, $strOrder) {
            $value1 = isset($arrItem1[$strKey]) ? $arrItem1[$strKey] : null;
            $value2 = isset($arrItem2[$strKey]) ? $arrItem2[$strKey] : null;
            if ($value1 == $value2) {
                return 0;
            }
            if ($strOrder == "asc") {
                return $value1 > $value2 ? 1 : -1;
            } else {
                return $value1 > $value2 ? -1 : 1;
            }
        };
        usort($arrData, $funcSort);
        return $arrData;
    }

    /**
        * @synopsis  delValue 删除数组中指定值的元素
        *
        * @param $arrData 要处理的数组
        * @param $delval 指定的值
        *
        * @returns   
     */
    public function delValue($arrData, $delval) {
        foreach($arrData as $key => $val) {
            if($val == $delval) {
                unset($arrData[$key]);
            }
        }
        return $arrData;
    }

    public function getKeyValue($arrData, $arrKey) {
        $arrValue = array();
        if(!is_array($arrData) || empty($arrData)) {
            return $arrValue;
        }
        foreach($arrData as $key => $val) {
            if(in_array($key, $arrKey)) {
                $arrValue[$key] = $val;
            }
        }
        return $arrValue;
    }

    public function delKeyValue($arrData, $arrKey) {
        if(!is_array($arrData) || empty($arrData)) {
            return array();
        }

        foreach($arrData as $key => $val) {
            if(in_array($key, $arrKey)) {
                unset($arrData[$key]);
            }
        }
        return $arrData;
    }

    public function merge($arr1, $arr2) {
        $arrValue = array();
        if(is_array($arr1)) {
            $arrValue = $arr1;
        }
        if(is_array($arr2)) {
            $arrValue = array_merge($arrValue, $arr2);
        }
        return $arrValue;
    }

    public function getBolOne(&$arrData) {
        $bolOne = false;
        if(is_string($arrData) || is_int($arrData)) {
            $bolOne = $arrData;
            $arrData = array($arrData);
        }
        return $bolOne;
    }
    
    public function getMapFieldValue($arrList, $strField, $strKey='id') {
        $arrData = array();
        if(!is_array($arrList) || empty($arrList)) {
            return $arrData;
        }
        foreach($arrList as $val) {
            if(isset($val[$strKey]) && isset($val[$strField])) {
                $arrData[$val[$strKey]] = $val[$strField]; 
            }
        }
        return $arrData;
    }
    
    public function keepField($arrList, $arrKeys) {
        foreach($arrList as &$val) {
            foreach($val as $key => $null) {
                if(!in_array($key, $arrKeys)) {
                    unset($val[$key]);
                }
            }
        }
        return $arrList;
    }
}
