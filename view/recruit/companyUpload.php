<?php

	include_once($_SERVER[DOCUMENT_ROOT]."/_lib/common.php");
	include_once($_SERVER[DOCUMENT_ROOT]."/_lib/function.php");
	$ori_name_nm = $_FILES['c_apply_form'] ['name'];
	
	$folder = 'applyinfo/'.date('Y').'/'.date('m');
	$upload_dir = $server_root_path.'/@upload/'.$folder.'/';
	echo "confirm file information <br />".$ori_name_nm;
			
	$save_file_detail = $upload_dir;
	$save_file_dir = '/'.$folder;
	$save_file_nm = "";

	
	$file_tmp = $_FILES['c_apply_form']['tmp_name'];
	$ori_name_array = explode('.',$ori_name_nm);
	$ext1 = array_pop($ori_name_array);
	$ori_name_array = implode("_", $ori_name_array);
	$save_file_nm = $save_file.'.'.$ext1;
	$upload_file = $upload_dir."dddd";
	$res = move_uploaded_file($file_tmp, $upload_file);
	 
 
 $uploadfile = $_FILES['upload'] ['name'];
 if($res){
  echo "파일이 업로드 되었습니다.<br />";
  echo "<img src ={$_FILES['upload']['name']}> <p>";
  echo "1. file name : {$_FILES['upload']['name']}<br />";
  echo "2. file type : {$_FILES['upload']['type']}<br />";
  echo "3. file size : {$_FILES['upload']['size']} byte <br />";
  echo "4. temporary file name : {$_FILES['upload']['size']}<br />";
 } else {
  echo "파일 업로드 실패 !! 다시 시도해주세요.<br />".$ori_name_nm;
 }
?>