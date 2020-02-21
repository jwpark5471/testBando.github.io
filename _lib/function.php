<?
//----------------------------------------------------------------------------------------
// Advice - html형식 변환
//----------------------------------------------------------------------------------------
function CheckWord($CheckValue) {
	if($CheckValue == null or $CheckValue == "") {
		return "";
	} else {
		$CheckValue = str_replace($CheckValue, "<", "&lt;");
		$CheckValue = str_replace($CheckValue, ">", "&gt;");
		$CheckValue = str_replace($CheckValue, "'", "&#39;");
		$CheckValue = str_replace($CheckValue, chr(13), "<br>");
		return $CheckValue;
	}
}

//----------------------------------------------------------------------------------------
// Advice - html태그 사용
//----------------------------------------------------------------------------------------
function CharWord($CheckValue) {
	if($CheckValue == null or $CheckValue == "") {
		return "";
	} else {
		$CheckValue = str_replace("\r\n", "<br>", $CheckValue);
		return $CheckValue;
	}
}

//----------------------------------------------------------------------------------------
// Advice - 문자 길이 컷트 후 .. 효과
// Parmeter Advice - str : 문자, num : 길이
//----------------------------------------------------------------------------------------
function GetCutSubject($str, $num) {
	if ($num >= mb_strlen($str)) return $str;
	/*
	상황에 따라 맞는걸 사용한다.
	return mb_strimwidth($str, '0', $num, '...', 'utf-8');	한글 : 3바이트
	return iconv_substr($str, 0, $num, "UTF-8")."...";		한글자당 1바이트
	*/
	return mb_strimwidth($str, '0', $num, '...', 'utf-8');
}

//----------------------------------------------------------------------------------------
// Advice - 배열값에 대한 특정값 유무 체크
// Parmeter Advice - strSplit : 전체 배열변수 (,로 구분) strFind : 찾을 값
//----------------------------------------------------------------------------------------
function GetSplitFindWord($strSplit, $strFind) {
	if($strSplit != "" and $strSplit != null and $strFind != "" and $strFind != null) {
		$strSplit = explode(",", $strSplit);
		$chk = false;
		for($i=0; $i<count($strSplit); $i++) {
			if(strstr($strSplit[$i], $strFind)) {
				echo strstr($strSplit[$i], $strFind);
				$chk = true;
			}
		}
		return $chk;
	} else {
		return false;
	}
}

//----------------------------------------------------------------------------------------
// Advice - method Get 형식으로 파라미터 생성
//----------------------------------------------------------------------------------------
function GetStr($str,$strName,$strValue) {

	if($str == "" or is_null($str)) {
		$str = "";
	} else {
		$str = $str."&";
	}
	return $str.$strName."=".$strValue;
}

//----------------------------------------------------------------------------------------
// Advice - 값 체크후 값이 없으면 전 페이지 이동 및 페이지 처리 종료
// Parmeter Advice - ProcType:처리 타입 형태 설정 ex) 01:alert창 및 전페이지 이동
//							02:XML형식 메시지 출력 그 외 response.end 처리
//----------------------------------------------------------------------------------------

function getParameterCheck($Param, $ProcType) {
	switch($ProcType) {
		case "01" :
			if($Param == "" or $Param == null) {
				JsAlertBack("정보가 정상적으로 전달되지 않았습니다.");
				exit;
			} else {
				return $Param;
			}
			break;
		case "02" :
			if($Param == "" or $Param == null) {
				echo "<resultmsg><![CDATA[정보가 정상적으로 전달되지 않았습니다.]]></resultmsg>";
				exit;
			} else {
				return $Param;
			}
			break;
		default :
			if($Param == "" or $Param == null) {
				exit;
			} else {
				return $Param;
			}
	}
}

//----------------------------------------------------------------------------------------
// Advice - 문자열을 날자 타입으로 변경
// Parmeter Advice - strdate:날자 타입으로 변경할 문자
// Parmeter Advice - ViewType:날자 타입 형태 설정 ex) 1 => 20110511 => 2011.05.11
//----------------------------------------------------------------------------------------
function GetDateType($strdate, $ViewType = 1) {
	if( $ViewType==1 ) {
		$year	=	substr($strdate,0,4);
		$mon	=	substr($strdate,5,2);
		$day	=	substr($strdate,8,2);
		$strdate	=	$year.".".$mon.".".$day;
	} else if( $ViewType==2 ) {
		$mon	=	substr($strdate,5,2);
		$day	=	substr($strdate,8,2);
		$strdate	=	$mon.".".$day;
	} else if( $ViewType==3 ) {
		$year	=	substr($strdate,0,4);
		$mon	=	substr($strdate,5,2);
		$day	=	substr($strdate,8,2);
		$time	=	substr($strdate,11,2);
		$minu	=	substr($strdate,14,2);
		$strdate	=	$year.".".$mon.".".$day." ".$time.":".$minu;
	} else if( $ViewType==4 ) {
		$year	=	substr($strdate,0,4);
		$mon	=	substr($strdate,5,2);
		$day	=	substr($strdate,8,2);
		$strdate	=	$year."년 ".$mon."월 ".$day."일";
	} else if( $ViewType==5 ) {
		$mon	=	substr($strdate,5,2);
		$day	=	substr($strdate,8,2);
		$strdate	=	$mon."월 ".$day."일";
	} else if( $ViewType==6) {
		$year	=	substr($strdate,0,4);
		$mon	=	substr($strdate,5,2);
		$day	=	substr($strdate,8,2);
		$strdate	=	$year.$mon.$day;
	} else if( $ViewType==7 ) {
		$year	=	substr($strdate,0,4);
		$mon	=	substr($strdate,5,2);
		$day	=	substr($strdate,8,2);
		$strdate	=	$year.". ".$mon.". ".$day;
	} else if( $ViewType==8 ) {
		$year	=	substr($strdate,2,2);
		$mon	=	substr($strdate,5,2);
		$day	=	substr($strdate,8,2);
		$strdate = $year.".".$mon.".".$day;
	} else if( $ViewType==9 ) {
		$year	=	substr($strdate,0,4);
		$mon	=	substr($strdate,5,2);
		$day	=	substr($strdate,8,2);
		$strdate = $year."-".$mon."-".$day;
	}
	return $strdate;
}

//----------------------------------------------------------------------------------------
// Advice - 자바스크립트 Alert 창
//----------------------------------------------------------------------------------------
function JsAlert($Msg) {
	echo "<script type='text/javascript'> alert('$Msg'); </script>";
}

//----------------------------------------------------------------------------------------
// Advice - 자바스크립트 Alert 창 및History.Back
//----------------------------------------------------------------------------------------
function JsAlertBack($Msg) {
	echo "<script type='text/javascript'> alert('$Msg'); history.back(); </script>";
}

//----------------------------------------------------------------------------------------
// Advice - 자바스크립트 메세지창 출력후 이동
// Parmeter Advice - (str:메세지명, strUrl:이동할주소)
//----------------------------------------------------------------------------------------
function JsAlertGo($strMsg, $strUrl) {
	echo "<script type='text/javascript'> alert('$strMsg'); location.replace('$strUrl'); </script>";
}

//----------------------------------------------------------------------------------------
// Advice - 자바스크립트 메세지창 출력후 이동
// Parmeter Advice - (str:메세지명, strUrl:이동할주소)
//----------------------------------------------------------------------------------------
function JsAlertPaGo($strMsg = "", $strUrl) {
	if($strMsg == "") {
		echo "<script type='text/javascript'> parent.location.replace('$strUrl'); </script>";
	}else{
		echo "<script type='text/javascript'> alert('$strMsg'); parent.location.replace('$strUrl'); </script>";
	}

}

//----------------------------------------------------------------------------------------
// Advice - 자바스크립트 창닫기
//----------------------------------------------------------------------------------------
function JsSelfClose($Msg = "") {
	if($Msg == "") {
		echo "<script type='text/javascript'> self.close(); </script>";
	} else {
		echo "<script type='text/javascript'> alert('$Msg'); self.close(); </script>";
	}
}

//----------------------------------------------------------------------------------------
// Advice - 자바스크립트 실행
//----------------------------------------------------------------------------------------
function JsExec($script) {
	echo "<script type='text/javascript'>".$script."</script>";
}

//----------------------------------------------------------------------------------------
// Advice - 주민번호로 나이확인
//----------------------------------------------------------------------------------------
function JAgeCount($Jumin) {
	if(substr($Jumin,0,2) > date("y")) {
		$JuminCount = "19".substr($Jumin,0,2);
	} else {
		$JuminCount = "20".substr($Jumin,0,2);
	}
	$JAgeCount = (date("Y") - $JuminCount) -1;

	if(date("m")*30 + date("d") >= substr($Jumin,2,2)*30 + substr($Jumin,4,2)) {
		$JAgeCount = $JAgeCount + 1;
	}

	return $JAgeCount;
}

//----------------------------------------------------------------------------------------
// Advice - 생년월일로 나이확인
//----------------------------------------------------------------------------------------
function YAgeCount($bdate) {
	$YAgeCount = (date("Y") - substr($bdate,0,4)) + 1;
	return $YAgeCount;
}

//----------------------------------------------------------------------------------------
// Advice - 암호화
//----------------------------------------------------------------------------------------
function GetBase64Encode($data) {
	$data = trim($data);

	if($data == "") {
		$return_data = "";
	} else {
		$return_data = base64_encode($data);
	}
	return $return_data;
}

//----------------------------------------------------------------------------------------
// Advice - 복호화
//----------------------------------------------------------------------------------------
function GetBase64Decode($data) {
	$data = trim($data);

	if($data == "") {
		$return_data = "";
	} else {
		$return_data = base64_decode($data);
	}
	return $return_data;
}


/*******************************************************************************************************
'----------------------------------------------------------------------------------------
' Advice - node 의 하위 자식노등중 nodeName 의 값을 반환한다.
'----------------------------------------------------------------------------------------
FUNCTION getNodeText(node, ByVal nodeName)

	If NOT node IS Nothing And NOT node.selectSingleNode(nodeName) IS Nothing then ' 오류 발생시 처리
		getNodeText = node.selectSingleNode(nodeName).text
	Else
		getNodeText = ""
	End if

END Function

Function getXMLCount(XML , CheckValue)

	dim XmlDoc,root,Maplist
	Set XmlDoc = Server.CreateObject("Microsoft.xmlDom")
	XmlDoc.loadXML XML

	Set root = XmlDoc.documentElement
	Set Maplist = root.selectSingleNode("//" & CheckValue)

	IF Typename(Maplist ) <> "Nothing" then
		getXMLCount = Maplist.childNodes.length
	else
		getXMLCount = 0
	end if
	Set root = nothing
	Set Maplist = nothing
	Set XmlDoc = nothing
End Function
*******************************************************************************************************/


//==================
//== 콤마 함수
//==================
function getComma($inputName) {
	if($inputName != "" and $inputName != null) {
		$comma = number_format($inputName);
		/*
		밑의 경우는 소수점 3째 자리에서 반올림하여 2째 자리까지 표현
		$comma = number_format($inputName,2);
		*/
	} else {
		$comma = $inputName;
	}
	return $comma;
}

//========================
//==원 표시
//========================
function getWon($inputName) {
	if($inputName != "" and $inputName != null) {
		$won = "￦".number_format($inputName);
	} else {
		$won = $inputName;
	}
	return $won;
}


//=========================================
//==날짜/주/월 더한 날 구하기
//=========================================
/*
asp 특화
Function TransAddDay(sdate, is_day)
	If isnull(is_day) or Not isnumeric(is_day) or len(is_day) < 1 Then
		is_day = 0
	End if

	TransEndDate = DATEADD("d",is_day, sdate)
End Function

Function TransAddWeek(sdate, is_day)
	If isnull(is_day) or Not isnumeric(is_day) or len(is_day) < 1 Then
		is_day = 0
	End if

	TransEndDate = DATEADD("w",is_day, sdate)
End Function


Function TransAddMonth(sdate, is_day)
	If isnull(is_day) or Not isnumeric(is_day) or len(is_day) < 1 Then
		is_day = 0
	End if

	TransEndDate = DATEADD("m",is_day, sdate)
End Function
*/
/*
기준일 2011-09-06
DateAdd(2)."<br>";  // 2 days after 2011-09-08
DateAdd(-6,0,"Y-m-d")."<br>";  // 2 days before with gigen format 2011-08-31
DateAdd(3,"01/01/2000");  // 3 days after given date  2000-01-04
*/
function DateAdd($v,$d=null , $f="Y-m-d"){
  $d=($d?$d:date("Y-m-d"));
  return date($f,strtotime($v." days",strtotime($d)));
}

//========================================
//= 문자열 함수
//========================================

function getTransWord($inputName) {

	$inputName = trim($inputName);
	$inputName = str_replace("%20", "", $inputName);
	$inputName = str_replace("--", "", $inputName);
	$inputName = str_replace("'", "", $inputName);
	$inputName = str_replace("script", "scripty", $inputName);
	//$inputName = str_replace("=", "", $inputName);
	//$inputName = str_replace(chr(13).chr(10),"<br>", $inputName);
	$inputName = str_replace(Chr(39),"''", $inputName);

	return $inputName;
}


//=============================================================================================
// trim and request
//=============================================================================================
function Unescape($str){
	return urldecode(preg_replace_callback('/%u([[:alnum:]]{4})/', 'UnescapeFunc', $str));
}
function UnescapeFunc($str){
	return iconv('UTF-16LE', 'UTF-8', chr(hexdec(substr($str[1], 2, 2))).chr(hexdec(substr($str[1],0,2))));
}



function Srequest($inDataStr) {
	$Srequest = trim(removeXSS(sqlFilter(Unescape($inDataStr))));
	return $Srequest;
}


function sqlFilter($search) {

	//필수 필터링 문자 리스트
	$strSearch[0] = "'";
	$strSearch[1] = "";
	$strSearch[2] = "%22";
	$strSearch[3] = "%";
	$strSearch[4] = "--";

	//변환될 필터 문자
	$strReplace[0] = "";
	$strReplace[1] = chr(13);
	$strReplace[2] = " ";
	$strReplace[3] = "";
	$strReplace[4] = "";

	$data = $search;
	for($cnt=0; $cnt < count($strSearch); $cnt++) {	//필터링 인덱스를 배열 크기와 맞춰준다.
		$data = str_replace(strtolower($strSearch[$cnt]), $strReplace[$cnt], $data);
	}
	return $data;
}

function removeXSS($get_String) {
	//get_String = Replace(get_String, "&", "&amp;")
	//$get_String = Replace(get_String, "<", "&lt;")
	//$get_String = Replace(get_String, ">", "&gt;")
	$get_String = str_replace("<xmp", "<x-xmo", $get_String);
	$get_String = str_replace("javascript", "<x-javascript", $get_String);
	$get_String = str_replace("script", "<x-script", $get_String);
	$get_String = str_replace("iframe", "<x-iframe", $get_String);
	$get_String = str_replace("document", "<x-document", $get_String);
	$get_String = str_replace("vbscript", "<x-vbscript", $get_String);
	$get_String = str_replace("applet", "<x-applet", $get_String);
	$get_String = str_replace("embed", "<x-embed", $get_String);
	$get_String = str_replace("object", "<x-object", $get_String);
	$get_String = str_replace("frame", "<x-frame", $get_String);
	$get_String = str_replace("grameset", "<x-grameset", $get_String);
	$get_String = str_replace("layer", "<x-layer", $get_String);
	$get_String = str_replace("bgsound", "<x-bgsound", $get_String);
	$get_String = str_replace("alert", "<x-alert", $get_String);
	$get_String = str_replace("onblur", "<x-onblur", $get_String);
	$get_String = str_replace("onchange", "<x-onchange", $get_String);
	$get_String = str_replace("onclick", "<x-onclick", $get_String);
	$get_String = str_replace("ondblclick","<x-ondblclick",  $get_String);
	$get_String = str_replace("enerror", "<x-enerror", $get_String);
	$get_String = str_replace("onfocus", "<x-onfocus", $get_String);
	$get_String = str_replace("onload", "<x-onload", $get_String);
	$get_String = str_replace("onmouse", "<x-onmouse", $get_String);
	$get_String = str_replace("onscroll", "<x-onscroll", $get_String);
	$get_String = str_replace("onsubmit", "<x-onsubmit", $get_String);
	$get_String = str_replace("onunload", "<x-onunload", $get_String);
	return $get_String;
}

function SetPageLIstCount( $TotalPage, $ListCount, $GetMsg, $SetColor, $SetCount ) {
	flush();
	ob_start();

	$PosPage = $_REQUEST['PosPage']; // 페이지 10단위
	$CurPage = $_REQUEST['CurPage'];	//페이지 1 ~10 사이
	$SetPage = $_REQUEST['SetPage'];	//페이지 1 ~10 사이

	//사용이미지
	$SetImagesE = "<img src=\"/_master/_images/PageImg/next.gif\"  >";
	$SetImagesS = "<img src=\"/_master/_images/PageImg/prev.gif\"   style=\"margin-right:3px;\">";
	$SetImagesEW = "<img src=\"/_master/_images/PageImg/next_w.gif\"  style=\"margin-right:3px;\">";
	$SetImagesSW = "<img src=\"/_master/_images/PageImg/prev_w.gif\"  >";

	//초기화
	if( $PosPage == "" || $CurPage  == 0 ) $CurPage = 1;
	if( $PosPage == "" ) $PosPage = 0;
	$i = 1;

	//초기화 2
	$lastPosPage = floor(( $TotalPage / $ListCount) / $SetCount );
	if( ( $TotalPage % $ListCount ) == 0  || $TotalPage <= $ListCount ) {
		$lastCurPage = ( floor( $TotalPage / $ListCount) % $SetCount );
		$TotalPage = ( floor( $TotalPage / $ListCount ) );
	} else {
		$lastCurPage = ( floor( $TotalPage / $ListCount) % $SetCount ) + 1;
		$TotalPage = (floor( $TotalPage / $ListCount ) ) + 1;
	}

	//초기화 3
	$GoUrl = $_SERVER['PHP_SELF']. '?';

	if( $GetMsg != "" ) {
		$GetMsg = "&" . $GetMsg;
	}

	//==========================================출력 color=======================================================
	$Scolor =  "<font color=\"". $SetColor ."\">";
	echo "<div id=\"PageDiv\" style=\"width:450px; margin:0 auto;\">";
	//==========================================출력1=======================================================
	echo "<a href=" . $GoUrl . "SetPage=".$SetPage."&CurPage=1&PosPage=0". $GetMsg." onfocus=\"this.blur();\">".$SetImagesSW."</a>";
	if( $PosPage > 0 ) {
		echo "<a href=" . $GoUrl . "SetPage=".$SetPage."&CurPage=1&PosPage=" . ( $PosPage-1 ). $GetMsg . " onfocus=\"this.blur();\">".  $Scolor.$SetImagesS."</a>&nbsp;</font> ";
	} else {
		echo $Scolor . $SetImagesS . "&nbsp;</font> ";
	}

	//==========================================출력2 반복=======================================================
	do {
		if( $i < $SetCount ) {
			$GUstr = "|";
		} else {
			$GUstr = "";
		}

		if( $TotalPage >= ( $i + ( $PosPage * $SetCount ) )  ) {
			if( $i == $CurPage ) {
				echo " <a href=" . $GoUrl . "SetPage=".$SetPage."&CurPage=" . $i . "&PosPage=" . $PosPage . $GetMsg .  " onfocus=this.blur();>" .  $Scolor . "<b>" .  (($PosPage * $SetCount) + $i) ."</b></font></a>&nbsp;<font color=#dddddd>".$GUstr."</font> ";
			} else {
				echo " <a href=" . $GoUrl . "SetPage=".$SetPage."&CurPage=" . $i . "&PosPage=" . $PosPage . $GetMsg .   " onfocus=\"this.blur();\">" . $Scolor . " " .( ( $PosPage * $SetCount ) + $i )."</font></a>&nbsp;<font color=\"#dddddd\">".$GUstr."</font> ";
			}
		} else {
			echo "<font color=\"#DDDDDD\">".( ( $PosPage * 10 ) + $i ). "</font> <font color=\"#dddddd\">". $GUstr . "</font>&nbsp;";
		}

		$i++;
		//echo $i;
		$k++;
		if( $k > 100 ) break;
	} while( $i<$SetCount + 1 );

	//==========================================출력3=======================================================
	if ( $TotalPage >  ( $PosPage * $SetCount ) + $SetCount ) {
		echo "<a href=" . $GoUrl . "SetPage=".$SetPage."&CurPage=1&PosPage=" . ($PosPage + 1) . $GetMsg . " onfocus=\"this.blur();\">" . $Scolor . $SetImagesE."</font></a>";
	} else {
		echo $Scolor  . $SetImagesE ."</font>";
	}

	echo "<a href=" . $GoUrl . "SetPage=".$SetPage."&CurPage=".$lastCurPage."&PosPage=". $lastPosPage . $GetMsg ." onfocus=\"this.blur();\">". $SetImagesEW ."</a>";
	//==========================================출력 color end =======================================================
	echo "</div>";
	if( $TotalPage < 2 ) {
		ob_end_clean();//만약 페이지가 하나일때 출력안함
	} else {
		ob_flush();
	}
}

function SetPageLIstFront( $PosPage, $CurPage, $SetPage, $TotalPage, $SetCount ) {
	flush();
	ob_start();

	//사용이미지
	$SetImagesSW = "<img src=\"/_resources/images/common/btn_paginate_first.png\" alt=\"\" />";
	$SetImagesS = "<img src=\"/_resources/images/common/btn_paginate_prev.png\" alt=\"\" />";
	$SetImagesE = "<img src=\"/_resources/images/common/btn_paginate_next.png\" alt=\"\" />";
	$SetImagesEW = "<img src=\"/_resources/images/common/btn_paginate_last.png\" alt=\"\" />";

	//초기화
	if( $PosPage == "" || $CurPage  == 0 ) $CurPage = 1;
	if( $PosPage == "" ) $PosPage = 0;
	$i = 1;

	//초기화 2
	$lastPosPage = floor(( $TotalPage / $SetPage) / $SetCount );
	if( ( $TotalPage % $SetPage ) == 0  || $TotalPage <= $SetPage ) {
		$lastCurPage = ( floor( $TotalPage / $SetPage) % $SetCount );
		$TotalPage = ( floor( $TotalPage / $SetPage ) );
	} else {
		$lastCurPage = ( floor( $TotalPage / $SetPage) % $SetCount ) + 1;
		$TotalPage = (floor( $TotalPage / $SetPage ) ) + 1;
	}

	//==========================================출력1=======================================================
	echo "<a href=\"#\" CurPage=\"1\" PosPage=\"0\" class=\"btn_first btn_page\">".$SetImagesSW."</a>".chr(10);
	if( $PosPage > 0 ) {
		echo "<a href=\"#\" CurPage=\"1\" PosPage=\"". ( $PosPage-1 ) ."\" class=\"btn_prev btn_page\">".$SetImagesS."</a>".chr(10);
	} else {
		echo "<a href=\"#\" CurPage=\"1\" PosPage=\"0\" class=\"btn_prev btn_page\">".$SetImagesS."</a>".chr(10);
	}

	//==========================================출력2 반복=======================================================
	do {
		if( $TotalPage >= ( $i + ( $PosPage * $SetCount ) )  ) {
			if( $i == $CurPage ) {
				echo "<strong><span>".(($PosPage * $SetCount) + $i)."</span></strong>".chr(10);
				if( $TotalPage > ( $i + ( $PosPage * $SetCount ) )  ) {
					echo "<img src=\"/_resources/images/common/ico_pagi_dot.png\" class=\"ico_dot\" alt=\"\" />".chr(10);
				}
			} else {
				echo "<a href=\"#\" CurPage=\"". $i ."\" PosPage=\"". $PosPage ."\" class=\"btn_page\"><span>" ." " .( ( $PosPage * $SetCount ) + $i )."</span></a>".chr(10);
				if( $TotalPage > ( $i + ( $PosPage * $SetCount ) )  ) {
					echo "<img src=\"/_resources/images/common/ico_pagi_dot.png\" class=\"ico_dot\" alt=\"\" />".chr(10);
				}
			}
		}
		$i++;
		$k++;
		if( $k > 100 ) break;
	} while( $i<$SetCount + 1 );

	//==========================================출력3=======================================================
	if ( $TotalPage >  ( $PosPage * $SetCount ) + $SetCount ) {
		echo "<a href=\"#\" CurPage=\"1\" PosPage=\"". ($PosPage + 1) ."\" class=\"btn_next btn_page\">" .$SetImagesE."</a>".chr(10);
	} else {
		echo "<a href=\"#\" CurPage=\"". $lastCurPage ."\" PosPage=\"". $lastPosPage ."\" class=\"btn_next btn_page\">" .$SetImagesE."</a>".chr(10);
	}
	echo "<a href=\"#\" CurPage=\"". $lastCurPage ."\" PosPage=\"". $lastPosPage ."\" class=\"btn_last btn_page\">". $SetImagesEW ."</a>".chr(10);
	if( $TotalPage < 2 ) {
		ob_end_clean();//만약 페이지가 하나일때 출력안함
	} else {
		ob_flush();
	}
}

function SetPageLIst( $TotalPage, $ListCount, $GetMsg, $SetCount ) {
	flush();
	ob_start();

	$PosPage = $_REQUEST['PosPage']; // 페이지 10단위
	$CurPage = $_REQUEST['CurPage'];	//페이지 1 ~10 사이

	//사용이미지
	$SetImagesE = '<img src="/@resources/images/common/btn_list_next_move.gif" alt="다음" />';
	$SetImagesS = '<img src="/@resources/images/common/btn_list_prev_move.gif" alt="이전" />';
	$SetImagesEW = '<img src="/@resources/images/common/btn_list_last_move.gif" alt="마지막으로" />';
	$SetImagesSW = '<img src="/@resources/images/common/btn_list_first_move.gif" alt="처음으로" />';


	$dis_SetImagesS = '<img src="/@resources/images/common/btn_list_prev_move.gif" alt="이전 페이지가 없습니다." />';
	$dis_SetImagesE = '<img src="/@resources/images/common/btn_list_next_move.gif" alt="다음 페이지가 없습니다." />';

	//초기화
	if( $PosPage == "" || $CurPage  == 0 ) $CurPage = 1;
	if( $PosPage == "" ) $PosPage = 0;
	$i = 1;

	//초기화 2
	$lastPosPage = floor(( $TotalPage / $ListCount) / $SetCount );
	if( ( $TotalPage % $ListCount ) == 0  || $TotalPage <= $ListCount ) {
		$lastCurPage = ( floor( $TotalPage / $ListCount) % $SetCount );
		$TotalPage = ( floor( $TotalPage / $ListCount ) );
	} else {
		$lastCurPage = ( floor( $TotalPage / $ListCount) % $SetCount ) + 1;
		$TotalPage = (floor( $TotalPage / $ListCount ) ) + 1;
	}

	//초기화 3
	$GoUrl = $_SERVER['PHP_SELF']. '?';

	if( $GetMsg != "" ) {
		$GetMsg = "&" . $GetMsg;
	}

	//==========================================출력1=======================================================
	echo "<a href=" . $GoUrl . "CurPage=1&PosPage=0". $GetMsg." class='btn_first' >".$SetImagesSW."</a>";
	if( $PosPage > 0 ) {
		echo "<a href=" . $GoUrl . "CurPage=1&PosPage=" . ( $PosPage-1 ). $GetMsg . " class='btn_prev' >".$SetImagesS."</a>";
	} else {
		echo "<span class='btn_prev'>".$dis_SetImagesS."</span>";
	}

	//==========================================출력2 반복=======================================================
	do {

		if( $TotalPage >= ( $i + ( $PosPage * $SetCount ) )  ) {
			if( $i == $CurPage ) {
				echo "<strong>".(($PosPage * $SetCount) + $i)."</strong>";
			} else {
				echo " <a href=" . $GoUrl . "CurPage=" . $i . "&PosPage=" . $PosPage . $GetMsg . " >".( ( $PosPage * $SetCount ) + $i )."</a>";
			}
		}

		$i++;
		$k++;
		if( $k > 100 ) break;
	} while( $i<$SetCount + 1 );

	//==========================================출력3=======================================================
	if ( $TotalPage >  ( $PosPage * $SetCount ) + $SetCount ) {
		echo "<a href=" . $GoUrl . "CurPage=1&PosPage=" . ($PosPage + 1) . $GetMsg . " class='btn_next' >". $SetImagesE."</a>";
	} else {
		echo "<span class='btn_next'>".$dis_SetImagesE ."</span>";
	}

	echo "<a href=" . $GoUrl . "CurPage=".$lastCurPage."&PosPage=". $lastPosPage . $GetMsg ." class='btn_last' >". $SetImagesEW ."</a>";
	//==========================================출력 color end =======================================================

	if( $TotalPage < 2 ) {
		ob_end_clean();//만약 페이지가 하나일때 출력안함
	} else {
		ob_flush();
	}
}

function GetStrFromReqValHid( $str ) {
	$ParseString = explode( "&", $str );
	$htmstr = "";

	foreach( $ParseString as $key=>$val ) {
		$tempArray = explode( "=", $val );
		$htmstr .= "<input type='hidden' name ='" .$tempArray[0]."' value='".$tempArray[1]."' >".chr(13).chr(10);
	}
	return $htmstr;
}

function GetNewImg($date, $newImg) {

	eregi("(.+)-(.+)-(.+) (.+):(.+):(.+)",$date,$temp);
	// $temp=preg_split("/[-,:, ]/", $date);  // 이게 더 보기 좋음
	$getTime = mktime($temp[4],$temp[5],$temp[6],$temp[2],$temp[3],$temp[1]);

	$nowTime = mktime();
	$returnStr = "";

	if(($nowTime - $getTime) < 86400) {
		$returnStr = $newImg;
	}
	return $returnStr;
}

// 문자열 HTML BR 형태 출력
function strHtmlBr($str) {
	$str = trim($str);
	$str = stripslashes($str);
	$str = str_replace("\n","<br>", $str);
	return $str;
}

// 문자열 BR HTML 형태 출력
function strBrHtml($str) {
	$str = trim($str);
	$str = stripslashes($str);
	$str = str_replace("<br>","\n", $str);
	return $str;
}

//----------------------------------------------------------------------------------------
// Advice - 문자열을 전화번호 타입으로 변경
// Parmeter Advice - strNum:전화번호 타입으로 변경할 문자
//----------------------------------------------------------------------------------------
function GetPhoneNumberType($strNum) {

	$strNum1 = substr($strNum, 0, 3);

	if (strlen($strNum) == 11) {
		$strNum2 = substr($strNum, 3, 4);
		$strNum3 = substr($strNum, 7, 4);
	} else {
		$strNum2 = substr($strNum, 3, 3);
		$strNum3 = substr($strNum, 6, 4);
	}

	return $strNum1."-".$strNum2."-".$strNum3;
}

//----------------------------------------------------------------------------------------
// Advice - Byte를 KB 또는 MB로 변환
// Parmeter Advice - iByte: 변환 할 Byte
//----------------------------------------------------------------------------------------
function convertByteUnit($iByte) {
	$strReturn = "";

	if ($iByte != null && $iByte != 0) {

		$iByte = $iByte / 1024;

		if ($iByte > 999) {
			$strReturn = "(".round(($iByte / 1024), 1)."MB)";
		} else {
			$strReturn = "(".round($iByte)."KB)";
		}
	}

	return $strReturn;
}

/*******************************
 * 필요 함수 추가 kim in ho
*******************************/

//날짜 비교  ( $date02 가 null 일때 오늘 날짜와 비교 )
function compareDate($date01, $date02=null)
{
	$date02 = ($date02 == null) ? date("Y-m-d") : $date02;

	$arDate01 = explode("-",$date01);
	$arDate02 = explode("-",$date02);

	$time01 = mktime(0,0,0,$arDate01[1],$arDate01[2],$arDate01[0]);
	$time02 = mktime(0,0,0,$arDate02[1],$arDate02[2],$arDate02[0]);

	$nResult = ($time01-$time02) / 86400;
	return $nResult;
	// $nResult 가 0 보다 크면 $date01 날짜가 더 큰값
}


function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}



function generateRandomStringSms($length = 10) {
    $characters = '0123456789';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function MakeDir($path) {
	$arrPath = explode("/",$path);
	$addPath = "";
	$parentPath = "";
	for($i=0;$i<count($arrPath);$i++){
		if (!empty($arrPath[$i])) {
			$addPath .= "/" . $arrPath[$i];
				var_dump($addPath);
			$pos = strpos($addPath,"/home/2020misexpo.org/public_html/@upload");
			if ($pos !== false) {
				chmod($addPath,0777); // 0775
			}

			if (!is_dir($addPath)) {
				try {
					$rtn = mkdir($addPath,0777,true);
					chmod($addPath,0777); // 0775
					//@mkdir($addPath);
				} catch (Exception  $e){
					print_r($e);
				}
			}
			$parentPath = $addPath;
		}
	}
	
}

// 트랜잭션
function begin(){
    mysql_query("BEGIN;");
}
function commit(){
    mysql_query("COMMIT;");
}
function rollback(){
    mysql_query("ROLLBACK;");
}



?>
