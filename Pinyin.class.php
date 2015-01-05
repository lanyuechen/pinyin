<?php

class Pinyin {

	var $map_1st;
	var $map_2nd;

	function __construct() {
		$tmp = file_get_contents('./pinyin-1st.json');
		$this->map_1st = json_decode($tmp);
		$tmp = file_get_contents('./pinyin-2nd.json');
		$this->map_2nd = json_decode($tmp);
	}

	public function get($str) {
		$str = $this->utf8_to_gbk($str);
		$res = $this->pins($str);
		return strtolower($res);
	}

	private function utf8_to_gbk($str){
		return iconv('UTF-8', 'GB2312//IGNORE', $str);
	}

	private function code_to_py($code){
		$map = $this->map_1st;
		foreach ($map as $k => $v) {
			if($code <= $v){
				return $k;
			}
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

