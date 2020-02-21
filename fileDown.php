<?php

//------------------------------------------------------
// download.php로 저장
//------------------------------------------------------

//$_ServerFileName=$sv; // 서버에 저장된 파일 이름
//$_RealSaveFileName=$rf; // 실제 사용자가 저장한 파일이름 & 사용자가 저장할 파일 이름
//
//$_DataDir = "../Data/";
//$_FilePath =  $_DataDir.$_ServerFileName;
$down_filename = $_GET['fileNm'];
if ($down_filename == 'han'){
	$down_filename =  '/home/2020misexpo.org/public_html/@download/applicationFormHan.hwp';
	$ori_filename = "미세먼저EXPO 기업참가신청서.hwp";
}else if($down_filename == 'word'){
	$down_filename =  '/home/2020misexpo.org/public_html/@download/applicationFormWord.docx';
	$ori_filename = "2020 미세먼지 EXPO 기업참가신청서.docx";
}

// $down_filename =  '/home/silkroad.mprd.co.kr/v2/@upload'. $down_filename;


//$down_filename = '/home/hosting_users/mprdcorp/www/@upload/applyinfo/2018/08/YkVWFajfHcqFHvDTWwV1.zip';
//$ori_filename = $_GET['oriFileNm'];




function fn_is_ie() {
	if(!isset($_SERVER['HTTP_USER_AGENT']))return false;
	if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false) return true; // IE8
	if(strpos($_SERVER['HTTP_USER_AGENT'], 'Windows NT 6.1') !== false) return true; // IE11
	if(strpos($_SERVER['HTTP_USER_AGENT'], 'rv:') !== false) return true;  // IE11
	if(strpos($_SERVER['HTTP_USER_AGENT'], 'Edge') !== false) return true; // Edge

	return false;
}


if (file_exists($down_filename))
{
	$ie_state = fn_is_ie();

//	header('Content-type: application/octet-stream');
//    header("Content-length: ".filesize($down_filename));
////    header('Content-Disposition: attachment; filename="'.iconv('UTF-8','euc-kr',$ori_filename). '"');
//    header('Content-Disposition: attachment; filename="'.$ori_filename. '"');
//    header("Content-Transfer-Encoding: binary");
//    Header("Cache-Control: cache,must-revalidate");
//    header("Pragma: public");
//    header("Expires: 0");

	header('Content-Description: File Transfer');
    //header('Content-Type: application/octet-stream');
	if( $ie_state ){
		header('Content-Disposition: attachment; filename="'.iconv('UTF-8','euc-kr',$ori_filename). '"');
//		header('Content-Disposition: attachment; filename="'. iconv('UTF-8','CP949//IGNORE',$ori_filename) .'"; filename*=utf-8\'\''. urlencode($ori_filename) .';');
	}else{
		header('Content-Disposition: attachment; filename='.$ori_filename);
	}
//	header('Content-Disposition: attachment; filename="'. iconv('UTF-8','CP949',$ori_filename) .'"; filename*=utf-8\'\''. urlencode($ori_filename) .';');
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Pragma: public');
    header('Content-Length: ' . filesize($down_filename));


    if (is_file("$down_filename"))
    {
        $fp = fopen("$down_filename", "r");
        if(!fpassthru($fp)) fclose($fp);
    }
}else{
	?>
		<meta charset="UTF-8" />
		<script>
		alert('존재하지 않는 파일입니다.');
		history.back();
		</script>
	<?
//	echo("존재하지 않는 파일입니다.");
}

?>
