<?


//if (!stristr($_SERVER[HTTP_HOST], "wooin.org")) {
// echo('Not Found');
// exit;
//}
//else if($_GET['a']==""){
// echo('wooin.org');
// exit;
//}

//var_dump(stristr($_SERVER[HTTP_HOST], "wooin.org"));


// session_cache_expire(900);
session_start();
/*******************************************************************************
** 공통 변수, 상수, 코드
*******************************************************************************/
error_reporting(E_ALL ^ E_NOTICE);
ini_set("display_errors", 1);

// 보안설정이나 프레임이 달라도 쿠키가 통하도록 설정
header('P3P: CP="ALL CURa ADMa DEVa TAIa OUR BUS IND PHY ONL UNI PUR FIN COM NAV INT DEM CNT STA POL HEA PRE LOC OTC"');

if (!isset($set_time_limit)) $set_time_limit = 0;
@set_time_limit($set_time_limit);

// 짧은 환경변수를 지원하지 않는다면
if (isset($HTTP_POST_VARS) && !isset($_POST)) {
	$_POST   = &$HTTP_POST_VARS;
	$_GET    = &$HTTP_GET_VARS;
	$_SERVER = &$HTTP_SERVER_VARS;
	$_COOKIE = &$HTTP_COOKIE_VARS;
	$_ENV    = &$HTTP_ENV_VARS;
	$_FILES  = &$HTTP_POST_FILES;

	if (!isset($_SESSION))
		$_SESSION = &$HTTP_SESSION_VARS;
}

if( !get_magic_quotes_gpc() ){
	if( is_array($_GET) ){
		while( list($k, $v) = each($_GET) ){
			if( is_array($_GET[$k]) ){
				while( list($k2, $v2) = each($_GET[$k]) ){
					$_GET[$k][$k2] = addslashes($v2);
				}
				@reset($_GET[$k]);
			}else{
				$_GET[$k] = addslashes($v);
			}
		}
		@reset($_GET);
	}

	if( is_array($_POST) ){
		while( list($k, $v) = each($_POST) ){
			if( is_array($_POST[$k]) ){
				while( list($k2, $v2) = each($_POST[$k]) ){
					$_POST[$k][$k2] = addslashes($v2);
				}
				@reset($_POST[$k]);
			}else{
				$_POST[$k] = addslashes($v);
			}
		}
		@reset($_POST);
	}

	if( is_array($_COOKIE) ){
		while( list($k, $v) = each($_COOKIE) ){
			if( is_array($_COOKIE[$k]) ){
				while( list($k2, $v2) = each($_COOKIE[$k]) ){
					$_COOKIE[$k][$k2] = addslashes($v2);
				}
				@reset($_COOKIE[$k]);
			}else{
				$_COOKIE[$k] = addslashes($v);
			}
		}
		@reset($_COOKIE);
	}
}

//==========================================================================================================================
// XSS(Cross Site Scripting) 공격에 의한 데이터 검증 및 차단
//--------------------------------------------------------------------------------------------------------------------------
function xss_clean($data){
	// If its empty there is no point cleaning it :\
	if(empty($data))
		return $data;

	// Recursive loop for arrays
	if(is_array($data)){
		foreach($data as $key => $value){
			$data[$key] = xss_clean($value);
		}

		return $data;
	}

	// http://svn.bitflux.ch/repos/public/popoon/trunk/classes/externalinput.php
	// +----------------------------------------------------------------------+
	// | Copyright (c) 2001-2006 Bitflux GmbH								 |
	// +----------------------------------------------------------------------+
	// | Licensed under the Apache License, Version 2.0 (the "License");	  |
	// | you may not use this file except in compliance with the License.	 |
	// | You may obtain a copy of the License at							  |
	// | http://www.apache.org/licenses/LICENSE-2.0						   |
	// | Unless required by applicable law or agreed to in writing, software  |
	// | distributed under the License is distributed on an "AS IS" BASIS,	|
	// | WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or	  |
	// | implied. See the License for the specific language governing		 |
	// | permissions and limitations under the License.					   |
	// +----------------------------------------------------------------------+
	// | Author: Christian Stocker <chregu@bitflux.ch>						|
	// +----------------------------------------------------------------------+

	// Fix &entity\n;
	$data = str_replace(array('&amp;','&lt;','&gt;'), array('&amp;amp;','&amp;lt;','&amp;gt;'), $data);
	$data = preg_replace('/(&#*\w+)[\x00-\x20]+;/', '$1;', $data);
	$data = preg_replace('/(&#x*[0-9A-F]+);*/i', '$1;', $data);

	if (function_exists("html_entity_decode")){
		$data = html_entity_decode($data);
	}else{
		$trans_tbl = get_html_translation_table(HTML_ENTITIES);
		$trans_tbl = array_flip($trans_tbl);
		$data = strtr($data, $trans_tbl);
	}

	// Remove any attribute starting with "on" or xmlns
	$data = preg_replace('#(<[^>]+?[\x00-\x20"\'])(?:on|xmlns)[^>]*+>#i', '$1>', $data);

	// Remove javascript: and vbscript: protocols
	$data = preg_replace('#([a-z]*)[\x00-\x20]*=[\x00-\x20]*([`\'"]*)[\x00-\x20]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#i', '$1=$2nojavascript...', $data);
	$data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#i', '$1=$2novbscript...', $data);
	$data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*-moz-binding[\x00-\x20]*:#', '$1=$2nomozbinding...', $data);

	// Only works in IE: <span style="width: expression(alert('Ping!'));"></span>
	$data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?expression[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
	$data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?behaviour[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
	$data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:*[^>]*+>#i', '$1>', $data);

	// Remove namespaced elements (we do not need them)
	$data = preg_replace('#</*\w+:\w[^>]*+>#i', '', $data);

	do{
		// Remove really unwanted tags
		$old_data = $data;
		$data = preg_replace('#</*(?:applet|b(?:ase|gsound|link)|embed|frame(?:set)?|i(?:frame|layer)|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|title|xml)[^>]*+>#i', '', $data);
	}
	while ($old_data !== $data);

	return $data;
}

$_GET = xss_clean($_GET);
//==========================================================================================================================


//==========================================================================================================================
// extract($_GET); 명령으로 인해 page.php?_POST[var1]=data1&_POST[var2]=data2 와 같은 코드가 _POST 변수로 사용되는 것을 막음
//--------------------------------------------------------------------------------------------------------------------------
$ext_arr = array ('PHP_SELF', '_ENV', '_GET', '_POST', '_FILES', '_SERVER', '_COOKIE', '_SESSION', '_REQUEST', 'HTTP_ENV_VARS', 'HTTP_GET_VARS', 'HTTP_POST_VARS', 'HTTP_POST_FILES', 'HTTP_SERVER_VARS', 'HTTP_COOKIE_VARS', 'HTTP_SESSION_VARS', 'GLOBALS');
$ext_cnt = count($ext_arr);
for ($i=0; $i<$ext_cnt; $i++) {
	if (isset($_GET[$ext_arr[$i]])) unset($_GET[$ext_arr[$i]]);
}
//==========================================================================================================================

// PHP 4.1.0 부터 지원됨
// php.ini 의 register_globals=off 일 경우
@extract($_GET);
@extract($_POST);
@extract($_SERVER);

// $member 에 값을 직접 넘길 수 있음
$config		= array();
$member		= array();
$gp			= array();
$present	= array();

if ($_COOKIE['idx'] != '') {
	$member['idx']		= $_COOKIE['idx'];
	$member['type']		= $_COOKIE['type'];
	$member['email1']	= $_COOKIE['email1'];
	$member['email2']	= $_COOKIE['email2'];
	$member['name']		= $_COOKIE['name'];
	$member['email']		= $_COOKIE['email'];
}

// 공통 경로 정의
if($_SERVER['SERVER_PROTOCOL'] == "HTTP/1.1"){
	$gp["url"]				= "http://".$_SERVER['HTTP_HOST'];
	$httpsMode				= "off";
}else{
	$gp["url"]				= "https://".$_SERVER['HTTP_HOST'];
	$httpsMode				= "on";
}

$queryString = "";
while( list($k, $v) = each($_GET) ){
	$queryString = $queryString."&".$k."=".addslashes($v);
}

// 짐 모바일인지 ?~
$m_bIsMobile = false;

$m_strUserAgent	= strtolower( $_SERVER['HTTP_USER_AGENT'] );
$arrMobilePhones = array("android", "blackberry", "nokia", "iemobile", "iphone", "ipad", "ipod", "opera mini", "opera mobi", "sonyericsson", "webos", "windows ce");

for ($i = 0; $i < count($arrMobilePhones); $i++) {
	if(strrpos($m_strUserAgent, $arrMobilePhones[$i])) {
		$m_bIsMobile = true;
	}
}

header("Pragma;no-cache");
header("Cache-Control;no-cache,must-revalidate");
session_start();

$siteName = '';

$server_root_path = $_SERVER['DOCUMENT_ROOT'] . '/';

include_once($_SERVER[DOCUMENT_ROOT]."/_lib/function.php");
include_once($_SERVER[DOCUMENT_ROOT]."/_lib/dbcon.php");

?>
