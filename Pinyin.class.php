<?php

class Pinyin {

	var $map_1st;
	var $map_2nd;
	var $code;
	var $py;

	function __construct() {
		$tmp = file_get_contents('./pinyin-1st.json');
		$this->map_1st = json_decode($tmp, 'array');
		$this->code = array_values($this->map_1st);
		$this->py = array_keys($this->map_1st);
		$tmp = file_get_contents('./pinyin-2nd.json');
		$this->map_2nd = json_decode($tmp, 'array');
	}

	public function get($str) {
		$str = $this->utf8_to_gbk($str);
		$res = $this->pins($str);
		return strtolower($res);
	}

	private function utf8_to_gbk($str){
		return iconv('UTF-8', 'GB2312//IGNORE', $str);
	}

	//二分查找,第一级汉字
	private function search($code, $min, $max){
		if($max - $min == 1){
			if($code > $this->code[$min]){
				return $this->py[$max];
			} else {
				return $this->py[$min];
			}
		} else {
			$mid = intval(($min + $max) / 2);
			if($code > $this->code[$mid]){
				return $this->search($code, $mid, $max);
			} else {
				return $this->search($code, $min, $mid);
			}
		}
	}

	private function code_to_py($code){
		if($code < 5590){
			return $this->search($code, 0, count($this->code) - 1);
		}
		$mapEx = $this->map_2nd;
		foreach ($mapEx as $k => $v) {
			if(in_array($code, $v)){
				return $k;
			}
		}
		return '';
	}

	private function pins($str){
		$res = '';
		for($i = 0; $i < strlen($str); $i++){
			$code = ord(substr($str, $i, 1));
			if($code > 160) {
				$code = $code - 160;
				$foo = ord(substr($str, ++$i, 1)) - 160; 
				$code = $code * 100 + $foo; 
			}
			$res .= $this->code_to_py($code);
		}
		return $res;
	}
}

