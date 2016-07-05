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
        $this->ci->load->database();
    }

    public function getListByConds($strTable, $arrFields, $arrConds, $strOrderBy = null, $strLimit = null) {
    	return 123;
    }
}
