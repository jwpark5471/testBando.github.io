<?
//----------------------------------------------------------------------------------------
// Advice - html���� ��ȯ
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
// Advice - html�±� ���
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
// Advice - ���� ���� ��Ʈ �� .. ȿ��
// Parmeter Advice - str : ����, num : ����
//----------------------------------------------------------------------------------------
function GetCutSubject($str, $num) {
	if ($num >= mb_strlen($str)) return $str;
	/*
	��Ȳ�� ���� �´°� ����Ѵ�.
	return mb_strimwidth($str, '0', $num, '...', 'utf-8');	�ѱ� : 3����Ʈ
	return iconv_substr($str, 0, $num, "UTF-8")."...";		�ѱ��ڴ� 1����Ʈ
	*/
	return mb_strimwidth($str, '0', $num, '...', 'utf-8');
}

//----------------------------------------------------------------------------------------
// Advice - �迭���� ���� Ư���� ���� üũ
// Parmeter Advice - strSplit : ��ü �迭���� (,�� ����) strFind : ã�� ��
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
// Advice - method Get �������� �Ķ���� ����
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
// Advice - �� üũ�� ���� ������ �� ������ �̵� �� ������ ó�� ����
// Parmeter Advice - ProcType:ó�� Ÿ�� ���� ���� ex) 01:alertâ �� �������� �̵�
//							02:XML���� �޽��� ��� �� �� response.end ó��
//----------------------------------------------------------------------------------------

function getParameterCheck($Param, $ProcType) {
	switch($ProcType) {
		case "01" :
			if($Param == "" or $Param == null) {
				JsAlertBack("������ ���������� ���޵��� �ʾҽ��ϴ�.");
				exit;
			} else {
				return $Param;
			}
			break;
		case "02" :
			if($Param == "" or $Param == null) {
				echo "<resultmsg><![CDATA[������ ���������� ���޵��� �ʾҽ��ϴ�.]]></resultmsg>";
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
// Advice - ���ڿ��� ���� Ÿ������ ����
// Parmeter Advice - strdate:���� Ÿ������ ������ ����
// Parmeter Advice - ViewType:���� Ÿ�� ���� ���� ex) 1 => 20110511 => 2011.05.11
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
		$strdate	=	$year."�� ".$mon."�� ".$day."��";
	} else if( $ViewType==5 ) {
		$mon	=	substr($strdate,5,2);
		$day	=	substr($strdate,8,2);
		$strdate	=	$mon."�� ".$day."��";
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
// Advice - �ڹٽ�ũ��Ʈ Alert â
//----------------------------------------------------------------------------------------
function JsAlert($Msg) {
	echo "<script type='text/javascript'> alert('$Msg'); </script>";
}

//----------------------------------------------------------------------------------------
// Advice - �ڹٽ�ũ��Ʈ Alert â ��History.Back
//----------------------------------------------------------------------------------------
function JsAlertBack($Msg) {
	echo "<script type='text/javascript'> alert('$Msg'); history.back(); </script>";
}

//----------------------------------------------------------------------------------------
// Advice - �ڹٽ�ũ��Ʈ �޼���â ����� �̵�
// Parmeter Advice - (str:�޼�����, strUrl:�̵����ּ�)
//----------------------------------------------------------------------------------------
function JsAlertGo($strMsg, $strUrl) {
	echo "<script type='text/javascript'> alert('$strMsg'); location.replace('$strUrl'); </script>";
}

//----------------------------------------------------------------------------------------
// Advice - �ڹٽ�ũ��Ʈ �޼���â ����� �̵�
// Parmeter Advice - (str:�޼�����, strUrl:�̵����ּ�)
//----------------------------------------------------------------------------------------
function JsAlertPaGo($strMsg = "", $strUrl) {
	if($strMsg == "") {
		echo "<script type='text/javascript'> parent.location.replace('$strUrl'); </script>";
	}else{
		echo "<script type='text/javascript'> alert('$strMsg'); parent.location.replace('$strUrl'); </script>";
	}

}

//----------------------------------------------------------------------------------------
// Advice - �ڹٽ�ũ��Ʈ â�ݱ�
//----------------------------------------------------------------------------------------
function JsSelfClose($Msg = "") {
	if($Msg == "") {
		echo "<script type='text/javascript'> self.close(); </script>";
	} else {
		echo "<script type='text/javascript'> alert('$Msg'); self.close(); </script>";
	}
}

//----------------------------------------------------------------------------------------
// Advice - �ڹٽ�ũ��Ʈ ����
//----------------------------------------------------------------------------------------
function JsExec($script) {
	echo "<script type='text/javascript'>".$script."</script>";
}

//----------------------------------------------------------------------------------------
// Advice - �ֹι�ȣ�� ����Ȯ��
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
// Advice - ������Ϸ� ����Ȯ��
//----------------------------------------------------------------------------------------
function YAgeCount($bdate) {
	$YAgeCount = (date("Y") - substr($bdate,0,4)) + 1;
	return $YAgeCount;
}

//----------------------------------------------------------------------------------------
// Advice - ��ȣȭ
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
// Advice - ��ȣȭ
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
' Advice - node �� ���� �ڽĳ���� nodeName �� ���� ��ȯ�Ѵ�.
'----------------------------------------------------------------------------------------
FUNCTION getNodeText(node, ByVal nodeName)

	If NOT node IS Nothing And NOT node.selectSingleNode(nodeName) IS Nothing then ' ���� �߻��� ó��
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
//== �޸� �Լ�
//==================
function getComma($inputName) {
	if($inputName != "" and $inputName != null) {
		$comma = number_format($inputName);
		/*
		���� ���� �Ҽ��� 3° �ڸ����� �ݿø��Ͽ� 2° �ڸ����� ǥ��
		$comma = number_format($inputName,2);
		*/
	} else {
		$comma = $inputName;
	}
	return $comma;
}

//========================
//==�� ǥ��
//========================
function getWon($inputName) {
	if($inputName != "" and $inputName != null) {
		$won = "��".number_format($inputName);
	} else {
		$won = $inputName;
	}
	return $won;
}


//=========================================
//==��¥/��/�� ���� �� ���ϱ�
//=========================================
/*
asp Ưȭ
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
������ 2011-09-06
DateAdd(2)."<br>";  // 2 days after 2011-09-08
DateAdd(-6,0,"Y-m-d")."<br>";  // 2 days before with gigen format 2011-08-31
DateAdd(3,"01/01/2000");  // 3 days after given date  2000-01-04
*/
function DateAdd($v,$d=null , $f="Y-m-d"){
  $d=($d?$d:date("Y-m-d"));
  return date($f,strtotime($v." days",strtotime($d)));
}

//========================================
//= ���ڿ� �Լ�
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

	//�ʼ� ���͸� ���� ����Ʈ
	$strSearch[0] = "'";
	$strSearch[1] = "";
	$strSearch[2] = "%22";
	$strSearch[3] = "%";
	$strSearch[4] = "--";

	//��ȯ�� ���� ����
	$strReplace[0] = "";
	$strReplace[1] = chr(13);
	$strReplace[2] = " ";
	$strReplace[3] = "";
	$strReplace[4] = "";

	$data = $search;
	for($cnt=0; $cnt < count($strSearch); $cnt++) {	//���͸� �ε����� �迭 ũ��� �����ش�.
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

	$PosPage = $_REQUEST['PosPage']; // ������ 10����
	$CurPage = $_REQUEST['CurPage'];	//������ 1 ~10 ����
	$SetPage = $_REQUEST['SetPage'];	//������ 1 ~10 ����

	//����̹���
	$SetImagesE = "<img src=\"/_master/_images/PageImg/next.gif\"  >";
	$SetImagesS = "<img src=\"/_master/_images/PageImg/prev.gif\"   style=\"margin-right:3px;\">";
	$SetImagesEW = "<img src=\"/_master/_images/PageImg/next_w.gif\"  style=\"margin-right:3px;\">";
	$SetImagesSW = "<img src=\"/_master/_images/PageImg/prev_w.gif\"  >";

	//�ʱ�ȭ
	if( $PosPage == "" || $CurPage  == 0 ) $CurPage = 1;
	if( $PosPage == "" ) $PosPage = 0;
	$i = 1;

	//�ʱ�ȭ 2
	$lastPosPage = floor(( $TotalPage / $ListCount) / $SetCount );
	if( ( $TotalPage % $ListCount ) == 0  || $TotalPage <= $ListCount ) {
		$lastCurPage = ( floor( $TotalPage / $ListCount) % $SetCount );
		$TotalPage = ( floor( $TotalPage / $ListCount ) );
	} else {
		$lastCurPage = ( floor( $TotalPage / $ListCount) % $SetCount ) + 1;
		$TotalPage = (floor( $TotalPage / $ListCount ) ) + 1;
	}

	//�ʱ�ȭ 3
	$GoUrl = $_SERVER['PHP_SELF']. '?';

	if( $GetMsg != "" ) {
		$GetMsg = "&" . $GetMsg;
	}

	//==========================================��� color=======================================================
	$Scolor =  "<font color=\"". $SetColor ."\">";
	echo "<div id=\"PageDiv\" style=\"width:450px; margin:0 auto;\">";
	//==========================================���1=======================================================
	echo "<a href=" . $GoUrl . "SetPage=".$SetPage."&CurPage=1&PosPage=0". $GetMsg." onfocus=\"this.blur();\">".$SetImagesSW."</a>";
	if( $PosPage > 0 ) {
		echo "<a href=" . $GoUrl . "SetPage=".$SetPage."&CurPage=1&PosPage=" . ( $PosPage-1 ). $GetMsg . " onfocus=\"this.blur();\">".  $Scolor.$SetImagesS."</a>&nbsp;</font> ";
	} else {
		echo $Scolor . $SetImagesS . "&nbsp;</font> ";
	}

	//==========================================���2 �ݺ�=======================================================
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

	//==========================================���3=======================================================
	if ( $TotalPage >  ( $PosPage * $SetCount ) + $SetCount ) {
		echo "<a href=" . $GoUrl . "SetPage=".$SetPage."&CurPage=1&PosPage=" . ($PosPage + 1) . $GetMsg . " onfocus=\"this.blur();\">" . $Scolor . $SetImagesE."</font></a>";
	} else {
		echo $Scolor  . $SetImagesE ."</font>";
	}

	echo "<a href=" . $GoUrl . "SetPage=".$SetPage."&CurPage=".$lastCurPage."&PosPage=". $lastPosPage . $GetMsg ." onfocus=\"this.blur();\">". $SetImagesEW ."</a>";
	//==========================================��� color end =======================================================
	echo "</div>";
	if( $TotalPage < 2 ) {
		ob_end_clean();//���� �������� �ϳ��϶� ��¾���
	} else {
		ob_flush();
	}
}

function SetPageLIstFront( $PosPage, $CurPage, $SetPage, $TotalPage, $SetCount ) {
	flush();
	ob_start();

	//����̹���
	$SetImagesSW = "<img src=\"/_resources/images/common/btn_paginate_first.png\" alt=\"\" />";
	$SetImagesS = "<img src=\"/_resources/images/common/btn_paginate_prev.png\" alt=\"\" />";
	$SetImagesE = "<img src=\"/_resources/images/common/btn_paginate_next.png\" alt=\"\" />";
	$SetImagesEW = "<img src=\"/_resources/images/common/btn_paginate_last.png\" alt=\"\" />";

	//�ʱ�ȭ
	if( $PosPage == "" || $CurPage  == 0 ) $CurPage = 1;
	if( $PosPage == "" ) $PosPage = 0;
	$i = 1;

	//�ʱ�ȭ 2
	$lastPosPage = floor(( $TotalPage / $SetPage) / $SetCount );
	if( ( $TotalPage % $SetPage ) == 0  || $TotalPage <= $SetPage ) {
		$lastCurPage = ( floor( $TotalPage / $SetPage) % $SetCount );
		$TotalPage = ( floor( $TotalPage / $SetPage ) );
	} else {
		$lastCurPage = ( floor( $TotalPage / $SetPage) % $SetCount ) + 1;
		$TotalPage = (floor( $TotalPage / $SetPage ) ) + 1;
	}

	//==========================================���1=======================================================
	echo "<a href=\"#\" CurPage=\"1\" PosPage=\"0\" class=\"btn_first btn_page\">".$SetImagesSW."</a>".chr(10);
	if( $PosPage > 0 ) {
		echo "<a href=\"#\" CurPage=\"1\" PosPage=\"". ( $PosPage-1 ) ."\" class=\"btn_prev btn_page\">".$SetImagesS."</a>".chr(10);
	} else {
		echo "<a href=\"#\" CurPage=\"1\" PosPage=\"0\" class=\"btn_prev btn_page\">".$SetImagesS."</a>".chr(10);
	}

	//==========================================���2 �ݺ�=======================================================
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

	//==========================================���3=======================================================
	if ( $TotalPage >  ( $PosPage * $SetCount ) + $SetCount ) {
		echo "<a href=\"#\" CurPage=\"1\" PosPage=\"". ($PosPage + 1) ."\" class=\"btn_next btn_page\">" .$SetImagesE."</a>".chr(10);
	} else {
		echo "<a href=\"#\" CurPage=\"". $lastCurPage ."\" PosPage=\"". $lastPosPage ."\" class=\"btn_next btn_page\">" .$SetImagesE."</a>".chr(10);
	}
	echo "<a href=\"#\" CurPage=\"". $lastCurPage ."\" PosPage=\"". $lastPosPage ."\" class=\"btn_last btn_page\">". $SetImagesEW ."</a>".chr(10);
	if( $TotalPage < 2 ) {
		ob_end_clean();//���� �������� �ϳ��϶� ��¾���
	} else {
		ob_flush();
	}
}

function SetPageLIst( $TotalPage, $ListCount, $GetMsg, $SetCount ) {
	flush();
	ob_start();

	$PosPage = $_REQUEST['PosPage']; // ������ 10����
	$CurPage = $_REQUEST['CurPage'];	//������ 1 ~10 ����

	//����̹���
	$SetImagesE = '<img src="/@resources/images/common/btn_list_next_move.gif" alt="����" />';
	$SetImagesS = '<img src="/@resources/images/common/btn_list_prev_move.gif" alt="����" />';
	$SetImagesEW = '<img src="/@resources/images/common/btn_list_last_move.gif" alt="����������" />';
	$SetImagesSW = '<img src="/@resources/images/common/btn_list_first_move.gif" alt="ó������" />';


	$dis_SetImagesS = '<img src="/@resources/images/common/btn_list_prev_move.gif" alt="���� �������� �����ϴ�." />';
	$dis_SetImagesE = '<img src="/@resources/images/common/btn_list_next_move.gif" alt="���� �������� �����ϴ�." />';

	//�ʱ�ȭ
	if( $PosPage == "" || $CurPage  == 0 ) $CurPage = 1;
	if( $PosPage == "" ) $PosPage = 0;
	$i = 1;

	//�ʱ�ȭ 2
	$lastPosPage = floor(( $TotalPage / $ListCount) / $SetCount );
	if( ( $TotalPage % $ListCount ) == 0  || $TotalPage <= $ListCount ) {
		$lastCurPage = ( floor( $TotalPage / $ListCount) % $SetCount );
		$TotalPage = ( floor( $TotalPage / $ListCount ) );
	} else {
		$lastCurPage = ( floor( $TotalPage / $ListCount) % $SetCount ) + 1;
		$TotalPage = (floor( $TotalPage / $ListCount ) ) + 1;
	}

	//�ʱ�ȭ 3
	$GoUrl = $_SERVER['PHP_SELF']. '?';

	if( $GetMsg != "" ) {
		$GetMsg = "&" . $GetMsg;
	}

	//==========================================���1=======================================================
	echo "<a href=" . $GoUrl . "CurPage=1&PosPage=0". $GetMsg." class='btn_first' >".$SetImagesSW."</a>";
	if( $PosPage > 0 ) {
		echo "<a href=" . $GoUrl . "CurPage=1&PosPage=" . ( $PosPage-1 ). $GetMsg . " class='btn_prev' >".$SetImagesS."</a>";
	} else {
		echo "<span class='btn_prev'>".$dis_SetImagesS."</span>";
	}

	//==========================================���2 �ݺ�=======================================================
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

	//==========================================���3=======================================================
	if ( $TotalPage >  ( $PosPage * $SetCount ) + $SetCount ) {
		echo "<a href=" . $GoUrl . "CurPage=1&PosPage=" . ($PosPage + 1) . $GetMsg . " class='btn_next' >". $SetImagesE."</a>";
	} else {
		echo "<span class='btn_next'>".$dis_SetImagesE ."</span>";
	}

	echo "<a href=" . $GoUrl . "CurPage=".$lastCurPage."&PosPage=". $lastPosPage . $GetMsg ." class='btn_last' >". $SetImagesEW ."</a>";
	//==========================================��� color end =======================================================

	if( $TotalPage < 2 ) {
		ob_end_clean();//���� �������� �ϳ��϶� ��¾���
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
	// $temp=preg_split("/[-,:, ]/", $date);  // �̰� �� ���� ����
	$getTime = mktime($temp[4],$temp[5],$temp[6],$temp[2],$temp[3],$temp[1]);

	$nowTime = mktime();
	$returnStr = "";

	if(($nowTime - $getTime) < 86400) {
		$returnStr = $newImg;
	}
	return $returnStr;
}

// ���ڿ� HTML BR ���� ���
function strHtmlBr($str) {
	$str = trim($str);
	$str = stripslashes($str);
	$str = str_replace("\n","<br>", $str);
	return $str;
}

// ���ڿ� BR HTML ���� ���
function strBrHtml($str) {
	$str = trim($str);
	$str = stripslashes($str);
	$str = str_replace("<br>","\n", $str);
	return $str;
}

//----------------------------------------------------------------------------------------
// Advice - ���ڿ��� ��ȭ��ȣ Ÿ������ ����
// Parmeter Advice - strNum:��ȭ��ȣ Ÿ������ ������ ����
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
// Advice - Byte�� KB �Ǵ� MB�� ��ȯ
// Parmeter Advice - iByte: ��ȯ �� Byte
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
 * �ʿ� �Լ� �߰� kim in ho
*******************************/

//��¥ ��  ( $date02 �� null �϶� ���� ��¥�� �� )
function compareDate($date01, $date02=null)
{
	$date02 = ($date02 == null) ? date("Y-m-d") : $date02;

	$arDate01 = explode("-",$date01);
	$arDate02 = explode("-",$date02);

	$time01 = mktime(0,0,0,$arDate01[1],$arDate01[2],$arDate01[0]);
	$time02 = mktime(0,0,0,$arDate02[1],$arDate02[2],$arDate02[0]);

	$nResult = ($time01-$time02) / 86400;
	return $nResult;
	// $nResult �� 0 ���� ũ�� $date01 ��¥�� �� ū��
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

// Ʈ�����
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
