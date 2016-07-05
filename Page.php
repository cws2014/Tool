<?php
/**
* @file Page.php
* @synopsis  分页类
* @author chenwensi
* @version 1
* @date 2016-07-05
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class CI_Page{
   	public $firstRow; // 起始行数
    public $listRows; // 列表每页显示行数
    public $parameter; // 分页跳转时要带的参数
    public $totalRows; // 总行数
    public $totalPages; // 分页总页面数
    public $rollPage   = 11;// 分页栏每页显示的页数
	public $lastSuffix = true; // 最后一页是否显示总页数

    private $p       = 'page'; //分页参数名
    private $url     = ''; //当前链接URL
    private $nowPage = 1;

	// 分页显示定制
    private $config  = array(
        'header' => '<span class="rows">共 %TOTAL_ROW% 条记录</span>',
        'prev'   => '上一页',
        'next'   => '下一页',
        'first'  => '首页',
        'last'   => '尾页',
        'current' => '<span class="current active">[page]</span>',
        'theme'  => '%HEADER% &nbsp;&nbsp; %FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% ',
        'page' => '<a class="num" href="[url]">[page]</a>',
        'prev_page' => '<a  class="prev" href="[url]">[prev]</a>',
        'next_page' => '<a  class="next" href="[url]">[next]</a>',
        'first_page' => '<a  class="first" href="[url]">[first]</a>',
        'last_page' => '<a  class="end" href="[url]">[last]</a>',
    );


    /**
     * 架构函数
     * @param array $totalRows  总的记录数
     * @param array $listRows  每页显示记录数
     * @param array $parameter  分页跳转的参数
     */
    public function __construct($arrInit) {
        /* 基础设置 */
		$this->url = $arrInit['pageurl'];
        $this->totalRows  = $arrInit['totalRows']; //设置总记录数
        $this->listRows   = $arrInit['listRows'];  //设置每页显示行数
        $this->parameter  = empty($arrInit['parameter']) ? $_GET : $arrInit['parameter'];
        $this->nowPage    = empty($_GET[$this->p]) ? 1 : intval($_GET[$this->p]);
        $this->nowPage    = $this->nowPage>0 ? $this->nowPage : 1;
        $this->firstRow   = $this->listRows * ($this->nowPage - 1);
        if (isset($arrInit['rollPage'])) {
            $this->rollPage = $arrInit['rollPage'];
        }
        if (isset($arrInit['lastSuffix'])) {
            $this->lastSuffix = $arrInit['lastSuffix'];
        }
    }

    /**
     * 定制分页链接设置
     * @param string $name  设置名称
     * @param string $value 设置值
     */
    public function setConfig($name,$value) {
        if(isset($this->config[$name])) {
            $this->config[$name] = $value;
        }
    }

    public function commconfig() {
        $config  = array(
            'header' => '',
            'prev'   => '上一页',
            'next'   => '下一页',
            'first'  => '首页',
            'last'   => '尾页',
            'current' => '<a class="active selected">[page]</a>',
            'theme'  => '%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%',
            'page' => '<a href="[url]">[page]</a>',
        );
        $this->config = $config;
    }

    /**
     * 生成链接URL
     * @param  integer $page 页码
     * @return string
     */
    private function url($page){
        return str_replace(urlencode('[PAGE]'), $page, $this->url);
    }
	
	private function buildParams($strurl, $arrParams) {
		if(strpos($strurl, '?') === false) {
			$strurl	.= '?';
        }
        foreach($arrParams as $key=>$val) {
            if(strpos($key, '/') !== false) {
                unset($arrParams[$key]);
            }
        }
        $strurl .= http_build_query($arrParams);
        return $strurl;
	}

    /**
     * 组装分页链接
     * @return string
     */
    public function show() {
        if(0 == $this->totalRows) return '';

        /* 生成URL */
        $this->parameter[$this->p] = '[PAGE]';
        $this->url = $this->buildParams($this->url, $this->parameter);
        /* 计算分页信息 */
        $this->totalPages = ceil($this->totalRows / $this->listRows); //总页数
        if(!empty($this->totalPages) && $this->nowPage > $this->totalPages) {
            $this->nowPage = $this->totalPages;
        }

        /* 计算分页零时变量 */
        $now_cool_page      = $this->rollPage/2;
		$now_cool_page_ceil = ceil($now_cool_page);
		$this->lastSuffix && $this->config['last'] = $this->totalPages;

        //上一页
        $up_row  = $this->nowPage - 1;
        //$up_page = $up_row > 0 ? '<a class="prev" href="' . $this->url($up_row) . '">' . $this->config['prev'] . '</a>' : '';
        $up_page = "";
        if ($up_row > 0){
            $up_page = str_replace(array('[url]','[page]', '[prev]'), array($this->url($up_row),$up_row, $this->config['prev']), $this->config['prev_page']); 
        }

        //下一页
        $down_row  = $this->nowPage + 1;
        //$down_page = ($down_row <= $this->totalPages) ? '<a class="next" href="' . $this->url($down_row) . '">' . $this->config['next'] . '</a>' : '';
        $down_page = "";
        if ($down_row <= $this->totalPages){
            $down_page = str_replace(array('[url]','[page]', '[next]'), array($this->url($down_row),$down_row, $this->config['next']), $this->config['next_page']); 
        }

        //第一页
        $the_first = '';
        if($this->totalPages > $this->rollPage && ($this->nowPage - $now_cool_page) >= 1){
            $the_first = str_replace(array('[url]','[page]', '[first]'), array($this->url(1),1, $this->config['first']), $this->config['first_page']); 

        }

        //最后一页
        $the_end = '';
        if($this->totalPages > $this->rollPage && ($this->nowPage + $now_cool_page) < $this->totalPages){
            $the_end = str_replace(array('[url]','[page]', '[last]'), array($this->url($this->totalPages),$this->totalPages, $this->config['last']), $this->config['last_page']); 
        }

        //数字连接
        $link_page = "";
        for($i = 1; $i <= $this->rollPage; $i++){
			if(($this->nowPage - $now_cool_page) <= 0 ){
				$page = $i;
			}elseif(($this->nowPage + $now_cool_page - 1) >= $this->totalPages){
				$page = $this->totalPages - $this->rollPage + $i;
			}else{
				$page = $this->nowPage - $now_cool_page_ceil + $i;
			}
            if($page > 0 && $page != $this->nowPage){

                if($page <= $this->totalPages){
                    $link_page .= str_replace(array('[url]','[page]'), array($this->url($page),$page), $this->config['page']);
                }else{
                    break;
                }
            }else{
                if($page > 0 && $this->totalPages != 1){
                    $link_page .= str_replace('[page]', $page, $this->config['current']);
                }
            }
        }

        //替换分页内容
        $page_str = str_replace(
            array('%HEADER%', '%NOW_PAGE%', '%UP_PAGE%', '%DOWN_PAGE%', '%FIRST%', '%LINK_PAGE%', '%END%', '%TOTAL_ROW%', '%TOTAL_PAGE%'),
            array($this->config['header'], $this->nowPage, $up_page, $down_page, $the_first, $link_page, $the_end, $this->totalRows, $this->totalPages),
            $this->config['theme']);
        return "{$page_str}";
    }
}

