<?php
class Pinyin {

	public function get($str) {
		$str = self :: utf8_to_gbk($str);
		$res = '';
		for($i=0; $i<strlen($str); $i++){
			$foo = self :: get_code($str);
			$res .= self :: pin($foo);
		}
		return strtolower($res);
	}

	protected function get_map(){
		$pytable = S('pytable');
		if (!$pytable) {
			$pytable = file_get_contents(__DIR__ . '/pinyin-1st.json');
			$pytable = json_decode($pytable);
			S('pytable', $pytable);
		}
		return $pytable;
	}

	protected function get_mapEx(){
		$pytableEx = S('pytableEx');
		if (!$pytableEx) {
			$pytableEx = file_get_contents(__DIR__ . '/pinyin-2nd.json');
			$pytableEx = json_decode($pytableEx);
			S('pytableEx', $pytableEx);
		}
		return $pytableEx;
	}

	protected function get_code($foo){
		$p = ord(substr($foo, 0, 1));
		if($p>160) {
			$p = $p - 160;
			$q = ord(substr($foo, 1, 1)) - 160; 
			$p = $p * 100 + $q; 
		}
		return $p;
	}

	protected function utf8_to_gbk($foo){
		return iconv('UTF-8', 'GB2312//IGNORE', $foo);
	}

	protected function pin($foo){
		$map = self :: get_map();
		foreach ($map as $k => $v) {
			if($foo <= $v){
				return $k;
			}
		}
		$mapEx = self :: get_mapEx();
		foreach ($mapEx as $k => $v) {
			if(in_array($foo, $v)){
				return $k;
			}
		}
		return '';
	}
}