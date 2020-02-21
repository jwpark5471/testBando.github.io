<?
	include_once($_SERVER[DOCUMENT_ROOT]."/_lib/common.php");
	include_once($_SERVER[DOCUMENT_ROOT]."/_lib/function.php");
	
	$mode = $_POST['mode'];
	
	if($mode == "view"){
		$view_director_name = $_POST["view_director_name"];
		$view_director_email = $_POST["view_director_email"];
		$view_director_phone = $_POST["view_director_phone"];
		$view_agree_form = $_POST["view_agree_form"];

		$viewApplySql .= " INSERT INTO view_applicant SET  ";
		$viewApplySql .= " 			view_director_name  = HEX(AES_ENCRYPT('$view_director_name','$aes_key')) ";
		$viewApplySql .= " 			,view_director_email = HEX(AES_ENCRYPT('$view_director_email','$aes_key')) ";
		$viewApplySql .= " 			,view_director_phone = HEX(AES_ENCRYPT('$view_director_phone','$aes_key')) ";
		$viewApplySql .= " 			,view_agree_form = '$view_agree_form' ";
		$viewApplySql .= " 			,view_reg_date = NOW() ";
		$sqlResult = mysql_query ( $viewApplySql, $connect );

		$a_idx = mysql_insert_id();


		if($sqlResult){
			$result->msg = '성공';
			$result->result = 1;
			$result = json_encode($result);
			echo $result;
		}else{
			$result->msg = '실패했습니다. 다시 시도해주세요.';
			$result->result = 0;
			$result = json_encode($result);
			echo $result;
		}


	}else if($mode == "company"){
		$c_apply_form = $_POST['c_apply_form'];
		$company_name = $_POST["company_name"];
		$company_business_type = $_POST["company_business_type"];
		$company_director_name = $_POST["company_director_name"];
		$company_director_phone = $_POST["company_director_phone"];
		$company_agree_form = $_POST["company_agree_form"];
	
		$folder = 'applyinfo/'.date('Y').'/'.date('m');
		$upload_dir = $_SERVER[DOCUMENT_ROOT].'/@upload/'.$folder.'/';
		
		if(!is_dir($upload_dir)){
		  MakeDir($upload_dir);
		}

		$save_file_detail = $upload_dir;
		$save_file_dir = '/'.$folder;
		$save_file_nm = ""
		if($companyApplyForm != ""){
			$save_file = generateRandomString(20);

			$ori_name_nm = $_FILES['c_apply_form']['name'];
			$file_tmp = $_FILES['c_apply_form']['tmp_name'];
			$ori_name_array = explode('.',$ori_name_nm);
			$ext1 = array_pop($ori_name_array);
			$ori_name_array = implode("_", $ori_name_array);
			$save_file_nm = $save_file.'.'.$ext1;
			$upload_file = $upload_dir.$save_file_nm;
			$res = move_uploaded_file($file_tmp, $upload_file);
		}
		
		if($res == ture){
		  $inFileSql = " INSERT INTO company_applicant_attach_file SET ";
		  $inFileSql .= "   ,caf_save_file_nm = '$save_file_nm' ";
		  $inFileSql .= "   ,caf_ori_file_nm = '$ori_name_nm' ";
		  $inFileSql .= "   ,caf_save_file_detail = '$save_file_detail' ";
		  $inFileSql .= "   ,caf_file_dir = '$save_file_dir' ";
		  mysql_query ( $inFileSql, $connect );
		  $fileIdx1 = mysql_insert_id();
		}
		
		$companyApplySql .= " INSERT INTO company_applicant SET  ";
		$companyApplySql .= " 			company_name  = HEX(AES_ENCRYPT('$company_name','$aes_key')) ";
		$companyApplySql .= " 			,company_business_type = '$company_business_type' ";
		$companyApplySql .= " 			,company_director_name = HEX(AES_ENCRYPT('$company_director_name','$aes_key')) ";
		$companyApplySql .= " 			,company_director_phone = HEX(AES_ENCRYPT('$company_director_phone','$aes_key')) ";
		$companyApplySql .= " 			,company_application_form_file = '$save_file_nm' ";
		$companyApplySql .= " 			,company_agree_form = '$company_agree_form' ";
		$companyApplySql .= " 			,company_reg_date = NOW() ";
		$sqlResult = mysql_query ( $companyApplySql, $connect );

		$a_idx = mysql_insert_id();


		if($sqlResult){
			$result->msg = '성공';
			$result->result = 1;
			$result = json_encode($result);
			echo $result;
		}else{
			$result->msg = '실패했습니다. 다시 시도해주세요.';
			$result->result = 0;
			$result = json_encode($result);
			echo $result;
		}
	}
?>
