<?php
/**
* @file Base_db.php
* @synopsis  数据库封装
* @author chenwensi
* @version 1
* @date 2016-07-05
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class CI_Base_db {

	private $ci;

    public function __construct() {
        $this->ci = &get_instance();
        // 加载其他类库，可以是自定义类库，也可以是ci类库
        // 其他加载模型，helper都可以这样
        //$this->ci->load->database();
    }

    public function getListByConds($strTable, $arrFields = array('*'), $arrConds = array(), $arrAppends = array()) {
    	$strSql = $this->getSqlString($strTable, $arrFields, $arrConds, $arrAppends);
        $this->db->query($strSql);
        return $this->db->array_result();
    }

    public function getRecordByConds($strTable, $arrFields = array('*'), $arrConds = array(), $arrAppends = array()) {
        $strSql = $this->getSqlString($strTable, $arrFields, $arrConds, $arrAppends);
        $this->db->query($strSql);
        $arrList = $this->db->array_result();
        if (count($arrList)) {
            return $arrList[0];
        }
        return array();
    }

    public function getCntByConds($strTable, $strCntField = "*", $arrConds = array()) {
        $strSql = "SELECT COUNT('{$strCntField}') AS num FROM `{$strTable}` WHERE ";
        $strSql .= $this->getWhereString($arrConds);
        $this->db->query($strSql);
        $arrList = $this->db->array_result();
        if (count($arrList)) {
            return $arrList[0]['num'];
        }
        return 0;
    }

    public function updateByConds($strTable, $arrUpdate = array(), $arrConds = array()) {
        if (empty($arrUpdate) || empty($arrConds)) {
            return false;
        }
        $strSql = "UPDATE `{$strTable}` SET ";
        foreach ($arrUpdate as $key => $val) {
            if (is_int($key)) {
                $strSql .= $val . ", ";
            } else {
                if (is_int($val)) {
                    $strSql .= $key . " = " . $val . ", ";
                } else {
                    $strSql .= $key . " = '" . $val . "', ";
                }
            }
        }
        $strSql = substr($strSql, 0, -2) . " ";
        $strSql .= $this->getWhereString($arrConds);
        if ($this->db->query($strSql)) {
            return true;
        }
        return false;
    }

    public function deleteByConds($strTable, $arrConds = array()) {
        if (empty($arrConds)) {
            return false;
        }
        $strSql = "DELETE FROM `{$strTable}` WHERE ";
        $strSql .= $this->getWhereString($arrConds);
        if ($this->db->query($strSql)) {
            return true;
        }
        return false;
    }

    public function insertResord($strTable, $arrInsert = array()) {
        if (empty($arrInsert)) {
            return false;
        }
        $this->db->insert($strTable, $arrInsert);
        return $this->db->insert_id();
    }

    public function getWhereString($arrConds) {
        $strRet = '';
        if (empty($arrConds)) {
            $strRet .= "1 = 1";
        } else {
            foreach ($arrConds as $key => $val) {
                if (is_int($key)) {
                    $strRet .= $val;
                } else {
                    $strRet .= $key . " = " . $val;
                }
                $strRet .= " AND ";
            }
            $strRet = substr($strRet, 0, -4);
        }
        return $strRet;
    }

    public function getSqlString($strTable, $arrFields, $arrConds, $arrAppends) {
        $strFields = implode(',', $arrFields);
        $strRet = "SELECT {$strFields} FROM `{$strTable}` WHERE ";
        $strRet .= $this->getWhereString($arrConds);
        if (!empty($arrAppends)) {
            foreach ($arrAppends as $val) {
                $strRet .= $val . " ";
            }
        }
        return $strRet;
    }
}
