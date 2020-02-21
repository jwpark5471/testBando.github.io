<?php
    namespace classes\apply;
    use classes\MyPDO as MyPDO;
    use classes\Common as Common;
    use classes\file\FileUpload as FileUpload;
    class Apply extends Common{
        private $a_idx = '';
        private $ai_idx = 1;

        private $s_date;
        private $e_date;
        private $now_date;
        public $dateYN = 'N';


        function __construct(){
            parent::__construct();
            $this->a_idx = $_REQUEST['a_idx'];
        }

        function getA_idx(){
            return $this->a_idx;
        }

        function getApplyDate(){
            try{
                $db = new MyPDO();

                $bindArray = array();
                $sql = " SELECT s_date, e_date FROM WIV2_APPLY_PERIOD WHERE ap_idx = 1  ";

                $stmt = $db->prepare($sql);
                $stmt->execute($bindArray);
                $result = $stmt->fetch();

                $this->s_date = $result->s_date;
                $this->e_date = $result->e_date;
                $this->now_date = date('Y-m-d');
                if($this->now_date >= $this->s_date && $this->now_date <= $this->e_date){
                    $this->dateYN = 'Y';
                }
            }catch(Exception $e){
                echo $e;
                exit;
            }
        }

        function login(){
            $a1_email = $_POST[user_id];
            $a1_pwd = $_POST[user_pw];

            try{
                $db = new MyPDO();
                $aesKey = $db->getAES_KEY();

                $bindArray = array();
                $sql = "  SELECT a_idx FROM WIV2_APPLICANT WHERE a1_email = HEX(AES_ENCRYPT('$a1_email','$aesKey')) AND a1_pwd = SHA('$a1_pwd')  ";

                $stmt = $db->prepare($sql);
                $stmt->execute($bindArray);
                $result = $stmt->fetch();

                $a_idx = $result->a_idx;

                return $a_idx;
            }catch(Exception $e){
                echo $e;
                exit;
            }
        }

        function searchPwd(){
            $a1_email = $_POST["user_email"];
            $a1_phone = $_POST["user_phone"];
            try{
                $db = new MyPDO();
                $aesKey = $db->getAES_KEY();

                $bindArray = array();
                $sql = "   SELECT
                                    a_idx, AES_DECRYPT(UNHEX(a1_email),'$aesKey') AS a1_email
                                    , AES_DECRYPT(UNHEX(a1_name),'$aesKey') AS a1_name
                                    , ( SELECT reset_pwd FROM WIV2_APPLY_PERIOD WHERE ap_idx = 1 ) as reset_pwd
                                FROM
                                    WIV2_APPLICANT
                                WHERE
                                    a1_email = HEX(AES_ENCRYPT('$a1_email','$aesKey'))
                                    AND a1_phone = HEX(AES_ENCRYPT('$a1_phone','$aesKey')) ";

                $stmt = $db->prepare($sql);
                $stmt->execute($bindArray);
                $result = $stmt->fetch();

                $view = array();

                $view['a_idx'] = $result->a_idx;
                $view['email'] = $result->a1_email;
                $view['name'] = $result->a1_name;
                $view['reset_pwd'] = $result->reset_pwd;

                return $view;
            }catch(Exception $e){
                echo $e;
                exit;
            }
        }

        function sendPwdEmail($view){
            try{
                $db = new MyPDO();
                $aesKey = $db->getAES_KEY();

                ///보낸 사람
        		$nameFrom  = "우인장학재단";
        		$mailFrom = "wooin@wooin.org";

        		$reset_pwd = 'wooin_'.$this->generateRandomString(5);

        		//받는 사람
    		    $nameTo  = $view['name'];
    		    $mailTo = $view['email'];


        		$cc = "";						//참조
        		//    $bcc = "wooin@wooin.org";						//숨은 참조
        		$bcc = "";						//숨은 참조

        		$subject = "우인장학재단 비밀번호 찾기";		//제목
        		$content .=	"<br>비밀번호는 : $reset_pwd 입니다.";

        		$charset = "UTF-8";

        		$nameFrom   = "=?$charset?B?".base64_encode($nameFrom)."?=";
        		$nameTo   = "=?$charset?B?".base64_encode($nameTo)."?=";
        		$subject = "=?$charset?B?".base64_encode($subject)."?=";

        		$header  = "Content-Type: text/html; charset=utf-8\r\n";
        		$header .= "MIME-Version: 1.0\r\n";
        		$header .= "Return-Path: <". $mailFrom .">\r\n";
        		$header .= "From: ". $nameFrom ." <". $mailFrom .">\r\n";
        		$header .= "Reply-To: <". $mailFrom .">\r\n";
        		if ($cc)  $header .= "Cc: ". $cc ."\r\n";
        		if ($bcc) $header .= "Bcc: ". $bcc ."\r\n";

        		$resultMail = mail($mailTo, $subject, $content, $header);

                if($resultMail){
                    try{
                        $db->beginTransaction();
                        $bindArray = array();
                        $sql = "    UPDATE WIV2_APPLICANT SET
                         			   a1_pwd = SHA(:reset_pwd)
                                    WHERE a_idx = :a_idx  ";

                        $bindArray[':reset_pwd'] = $reset_pwd;
                        $bindArray[':a_idx'] = $view[a_idx];

                        $stmt = $db->prepare($sql);
                        $stmt->execute($bindArray);

                        $db->commit();
                    }catch(PDOExecption $e){
                        return false;
                    }
                }

                return $resultMail;

            }catch(Exception $e){
                echo $e;
                exit;
            }
        }

        function emailCheck(){
            $name = $_POST["a1_name"];
        	$email = $_POST["a1_email"];
            try{
                $db = new MyPDO();
                $aesKey = $db->getAES_KEY();

                $bindArray = array();
                $sql = "  SELECT count(*) as cnt FROM WIV2_APPLICANT WHERE a1_email = HEX(AES_ENCRYPT(:a1_email,'$aesKey')) ";
                $bindArray[':a1_email'] = $email;

                $stmt = $db->prepare($sql);
                $stmt->execute($bindArray);
                $result = $stmt->fetch();

                $cnt = $result->cnt;

                return $cnt;
            }catch(Exception $e){
                echo $e;
                exit;
            }
        }

        function emailAuth(){
            $name = $_POST["a1_name"];
        	$email = $_POST["a1_email"];
            try{
                $db = new MyPDO();
                $aesKey = $db->getAES_KEY();
                $sendEmailAuth = $this->generateRandomString(5);
                try{
                        $db->beginTransaction();

                        $bindArray = array();
                        $sql = " INSERT INTO WIV2_EMAIL_SEND SET
                                	   a1_email = HEX(AES_ENCRYPT(:a1_email,'$aesKey'))
                                	   ,auth_code = :sendEmailAuth
                                	   ,reg_date = NOW() ";

                        $bindArray[':a1_email'] = $email;
                        $bindArray[':sendEmailAuth'] = $sendEmailAuth;

                        $stmt = $db->prepare($sql);
                        $stmt->execute($bindArray);

                        $db->commit();
                }catch(PDOExecption $e){
                    return;
                }
                return $sendEmailAuth;
            }catch(Exception $e){
                echo $e;
                exit;
            }
        }

        function sendEmailAuth($sendEmailAuth){
            $name = $_POST["a1_name"];
            $email = $_POST["a1_email"];
            try{
                ///보낸 사람
            	$nameFrom  = "우인장학재단";
            	$mailFrom = "wooin@wooin.org";

            	//받는 사람
               	$nameTo  = "$name";
               	$mailTo = "$email";

            	$cc = "";						//참조
            	//    $bcc = "wooin@wooin.org";						//숨은 참조
            	$bcc = "";						//숨은 참조

            	$subject = "우인장학재단 온라인 신청 접수 이메일 인증";		//제목
            	$content =	"인증번호는 : $sendEmailAuth 입니다.";

            	$charset = "UTF-8";

            	$nameFrom   = "=?$charset?B?".base64_encode($nameFrom)."?=";
            	$nameTo   = "=?$charset?B?".base64_encode($nameTo)."?=";
            	$subject = "=?$charset?B?".base64_encode($subject)."?=";

            	$header  = "Content-Type: text/html; charset=utf-8\r\n";
            	$header .= "MIME-Version: 1.0\r\n";
            	$header .= "Return-Path: <". $mailFrom .">\r\n";
            	$header .= "From: ". $nameFrom ." <". $mailFrom .">\r\n";
            	$header .= "Reply-To: <". $mailFrom .">\r\n";
            	if ($cc)  $header .= "Cc: ". $cc ."\r\n";
            	if ($bcc) $header .= "Bcc: ". $bcc ."\r\n";

            	$resultMail = mail($mailTo, $subject, $content, $header, '-f'.$mailFrom);

                return $resultMail;

            }catch(Exception $e){
                echo $e;
                exit;
            }
        }

        function emailAuthCode(){
            $a1_email = $_REQUEST[a1_email];
            $authCode = $_REQUEST[authCode];
            try{
                $db = new MyPDO();
                $aesKey = $db->getAES_KEY();

                $bindArray = array();
                $sql = "     SELECT
                                idx,a1_email,auth_code
                            FROM
                                WIV2_EMAIL_SEND
                            WHERE
                                a1_email IS NOT NULL
                                AND a1_email = HEX(AES_ENCRYPT(:a1_email,'$aesKey'))
                            ORDER BY idx DESC LIMIT 1 ";
                $bindArray[':a1_email'] = $a1_email;

                $stmt = $db->prepare($sql);
                $stmt->execute($bindArray);
                $result = $stmt->fetch();

                $authCodeDB = $result->auth_code;

                if($authCode == $authCodeDB && $authCodeDB != ""){
                    $result = true;
                }else{
                    $result = false;
                }

                return $result;
            }catch(Exception $e){
                echo $e;
                exit;
            }
        }



        function getViewAll(){
            try{
                $db = new MyPDO();
                $aesKey = $db->getAES_KEY();

                $bindArray = array();
                $sql = "  SELECT
                        		A.a_idx,
                        		A.ai_idx,
                        		A.a1_gubun,
                        		AES_DECRYPT(UNHEX(A.a1_name),'$aesKey') AS a1_name,
                        		AES_DECRYPT(UNHEX(A.a1_tel),'$aesKey') AS a1_tel,
                        		AES_DECRYPT(UNHEX(A.a1_phone),'$aesKey') AS a1_phone,
                        		A.a1_gender,
                        		AES_DECRYPT(UNHEX(A.a1_birth),'$aesKey') AS a1_birth,
                        		AES_DECRYPT(UNHEX(A.a1_email),'$aesKey') AS a1_email,
                        		A.a1_pwd,
                        		A.a1_reg_date,
                        		A.a1_up_date,
                        		A.a1_up_state,
                        		AES_DECRYPT(UNHEX(A.a2_addr),'$aesKey') AS a2_addr,
                        		AES_DECRYPT(UNHEX(A.a2_addr_detail),'$aesKey') AS a2_addr_detail,
                        		A.a2_school_type,
                        		A.a2_school_name,
                        		A.a2_school_h_department,
                        		A.a2_school_h_department_text,
                        		A.a2_school_u_department,
                        		A.a2_school_year,
                        		A.a2_school_semester,
                        		A.a2_school_gubun,
                        		A.a2_school_gubun_text,
                        		A.a2_school_add_text,
                        		A.a2_ab_add_text,
                        		A.a2_bank_gubun,
                        		A.a2_bank_gubun_text,
                        		AES_DECRYPT(UNHEX(A.a2_bank_name),'$aesKey') AS a2_bank_name,
                        		AES_DECRYPT(UNHEX(A.a2_bank_account),'$aesKey') AS a2_bank_account,
                        		AES_DECRYPT(UNHEX(A.a2_bank_account_holder),'$aesKey') AS a2_bank_account_holder,
                        		A.a2_protector_gubun,
                        		A.a2_protector_gubun_text,
                        		AES_DECRYPT(UNHEX(A.a2_protector_name),'$aesKey') AS a2_protector_name,
                        		AES_DECRYPT(UNHEX(A.a2_protector_tel),'$aesKey') AS a2_protector_tel,
                        		A.a2_reg_date,
                        		A.a2_up_date,
                        		A.a2_up_state,
                        		A.a3_self_introduction_1,
                        		A.a3_self_introduction_2,
                        		A.a3_self_introduction_3,
                        		A.a3_self_introduction_4,
                        		A.a3_reg_date,
                        		A.a3_up_date,
                        		A.a3_up_state,
                        		A.a4_file_1,
                        		A.a4_file_2,
                        		A.a4_file_3,
                        		A.a4_file_3_gubun,
                        		A.a4_agree_1,
                        		A.a4_agree_2,
                        		A.a4_reg_date,
                        		A.a4_up_date,
                        		A.a4_up_state,
                                B.af_idx AS af_idx1, B.af_type AS af_type1, B.af_save_file_nm AS af_save_file_nm1, B.af_ori_file_nm AS af_ori_file_nm1, B.af_save_file_detail AS af_save_file_detail1, B.af_file_dir AS af_file_dir1,
                                C.af_idx AS af_idx2, C.af_type AS af_type2, C.af_save_file_nm AS af_save_file_nm2, C.af_ori_file_nm AS af_ori_file_nm2, C.af_save_file_detail AS af_save_file_detail2, C.af_file_dir AS af_file_dir2,
                                D.af_idx AS af_idx3, D.af_type AS af_type3, D.af_save_file_nm AS af_save_file_nm3, D.af_ori_file_nm AS af_ori_file_nm3, D.af_save_file_detail AS af_save_file_detail3, D.af_file_dir AS af_file_dir3
                         FROM WIV2_APPLICANT A
                            LEFT JOIN WIV2_APPLY_ATTACH_FILE B ON A.a4_file_1 = B.af_idx
                            LEFT JOIN WIV2_APPLY_ATTACH_FILE C ON A.a4_file_2 = C.af_idx
                            LEFT JOIN WIV2_APPLY_ATTACH_FILE D ON A.a4_file_3 = D.af_idx
                         WHERE a_idx = :a_idx ";
                $bindArray[':a_idx'] = $this->a_idx;

                $stmt = $db->prepare($sql);
                $stmt->execute($bindArray);
                $result = $stmt->fetch();

                $view = array();

                $view['a_idx'] = $result->a_idx;
        		$view['ai_idx'] = $result->ai_idx;
        		$view['a1_gubun'] = $result->a1_gubun;
        		$view['a1_name'] = $result->a1_name;
        		$view['a1_tel'] = strlen($result->a1_tel) > 8 ?$result->a1_tel : "" ;
        		$view['a1_phone'] = $result->a1_phone;
        		$view['a1_gender'] = $result->a1_gender;
        		$view['a1_birth'] = $result->a1_birth;
        		$view['a1_email'] = $result->a1_email;
        		$view['a1_pwd'] = $result->a1_pwd;
        		$view['a1_reg_date'] = $result->a1_reg_date;
        		$view['a1_up_date'] = $result->a1_up_date;
        		$view['a1_up_state'] = $result->a1_up_state;
        		$view['a2_addr'] = $result->a2_addr;
        		$view['a2_addr_detail'] = $result->a2_addr_detail;
        		$view['a2_school_type'] = $result->a2_school_type;
        		$view['a2_school_name'] = $result->a2_school_name;
        		$view['a2_school_h_department'] = $result->a2_school_h_department;
        		$view['a2_school_h_department_text'] = $result->a2_school_h_department_text;
        		$view['a2_school_u_department'] = $result->a2_school_u_department;
        		$view['a2_school_year'] = $result->a2_school_year;
        		$view['a2_school_semester'] = $result->a2_school_semester;
        		$view['a2_school_gubun'] = $result->a2_school_gubun;
        		$view['a2_school_gubun_text'] = $result->a2_school_gubun_text;
        		$view['a2_school_add_text'] = $result->a2_school_add_text;
        		$view['a2_ab_add_text'] = $result->a2_ab_add_text;
        		$view['a2_bank_gubun'] = $result->a2_bank_gubun;
        		$view['a2_bank_gubun_text'] = $result->a2_bank_gubun_text;
        		$view['a2_bank_name'] = $result->a2_bank_name;
        		$view['a2_bank_account'] = $result->a2_bank_account;
        		$view['a2_bank_account_holder'] = $result->a2_bank_account_holder;
        		$view['a2_protector_gubun'] = $result->a2_protector_gubun;
        		$view['a2_protector_gubun_text'] = $result->a2_protector_gubun_text;
        		$view['a2_protector_name'] = $result->a2_protector_name;
        		$view['a2_protector_tel'] = $result->a2_protector_tel;
        		$view['a2_reg_date'] = $result->a2_reg_date;
        		$view['a2_up_date'] = $result->a2_up_date;
        		$view['a2_up_state'] = $result->a2_up_state;
                $view['a2_academic_backgound_list'] = $this->getBackGroundList($this->a_idx);

                $view['a3_self_introduction_1'] = preg_replace("/\\\\/","", str_replace("\r\n", "<br>", $result->a3_self_introduction_1));
        		$view['a3_self_introduction_2'] = preg_replace("/\\\\/","", str_replace("\r\n", "<br>", $result->a3_self_introduction_2));
        		$view['a3_self_introduction_3'] = preg_replace("/\\\\/","", str_replace("\r\n", "<br>", $result->a3_self_introduction_3));
        		$view['a3_self_introduction_4'] = preg_replace("/\\\\/","", str_replace("\r\n", "<br>", $result->a3_self_introduction_4));

                $view['a3_reg_date'] = $result->a3_reg_date;
        		$view['a3_up_date'] = $result->a3_up_date;
        		$view['a3_up_state'] = $result->a3_up_state;
        		$view['a4_file_1'] = $result->a4_file_1;
        		$view['a4_file_2'] = $result->a4_file_2;
        		$view['a4_file_3'] = $result->a4_file_3;
        		$view['a4_file_3_gubun'] = $result->a4_file_3_gubun;
        		$view['a4_file_3_gubun_text'] =  "해당없음";
                if($result->a4_file_3_gubun == ""){
        			$view['a4_file_3_gubun_text'] = "해당없음";
        		}else if($result->a4_file_3_gubun == 0){
        			$view['a4_file_3_gubun_text'] = "기초생활수급자";
        		}else if($result->a4_file_3_gubun == 1){
        			$view['a4_file_3_gubun_text'] = "차상위계층";
        		}
        		$view['a4_agree_1'] = $result->a4_agree_1;
        		$view['a4_agree_2'] = $result->a4_agree_2;
        		$view['a4_reg_date'] = $result->a4_reg_date;
        		$view['a4_up_date'] = $result->a4_up_date;
        		$view['a4_up_state'] = $result->a4_up_state;

                $view['applyFileIdx1'] = $result->af_idx1;
                $view['saveFileNm1'] = $result->af_save_file_nm1;
                $view['oriFileNm1'] = $result->af_ori_file_nm1;
                $view['saveFileDetail1'] = $result->af_save_file_detail1;
                $view['saveFileDir1'] = $result->af_file_dir1;

                $view['applyFileIdx2'] = $result->af_idx2;
                $view['saveFileNm2'] = $result->af_save_file_nm2;
                $view['oriFileNm2'] = $result->af_ori_file_nm2;
                $view['saveFileDetail2'] = $result->af_save_file_detail2;
                $view['saveFileDir2'] = $result->af_file_dir2;

                $view['applyFileIdx3'] = $result->af_idx3;
                $view['saveFileNm3'] = $result->af_save_file_nm3;
                $view['oriFileNm3'] = $result->af_ori_file_nm3;
                $view['saveFileDetail3'] = $result->af_save_file_detail3;
                $view['saveFileDir3'] = $result->af_file_dir3;

                $view['applyFileIdx4'] = $result->af_idx4;
                $view['saveFileNm4'] = $result->af_save_file_nm4;
                $view['oriFileNm4'] = $result->af_ori_file_nm4;
                $view['saveFileDetail4'] = $result->af_save_file_detail4;
                $view['saveFileDir4'] = $result->af_file_dir4;

                $view['fileDown1'] = $result->af_file_dir1.'/'.$result->af_save_file_nm1;
                $view['fileDownNm1'] = $result->af_ori_file_nm1;
                if($result->af_idx1 != ""){
                    $fileNm = $result->af_file_dir1 . '/' . $result->af_save_file_nm1;
                    $view['fileDownUrl1'] = $this->getViewUrl . '/fileDown.php?fileNm=' .$fileNm. '&oriFileNm='. $result->af_ori_file_nm1;
                }

                $view['fileDown2'] = $result->af_file_dir2.'/'.$result->af_save_file_nm2;
                $view['fileDownNm2'] = $result->af_ori_file_nm2;
                if($result->af_idx2 != ""){
                    $fileNm = $result->af_file_dir2 . '/' . $result->af_save_file_nm2;
                    $view['fileDownUrl2'] = $this->getViewUrl . '/fileDown.php?fileNm=' .$fileNm. '&oriFileNm='. $result->af_ori_file_nm2;
                }

                $view['fileDown3'] = $result->af_file_dir3.'/'.$result->af_save_file_nm3;
                $view['fileDownNm3'] = $result->af_ori_file_nm3;
                if($result->af_idx3 != ""){
                    $fileNm = $result->af_file_dir3 . '/' . $result->af_save_file_nm3;
                    $view['fileDownUrl3'] = $this->getViewUrl . '/fileDown.php?fileNm=' .$fileNm. '&oriFileNm='. $result->af_ori_file_nm3;
                }

                return $view;

            }catch(Exception $e){
                echo $e;
                exit;
            }
        }

        //리스트
        function getBackGroundList($a_idx){
            try{
                $db = new MyPDO();

                $bindArray = array();
                $sql = "    SELECT
                        	  	idx,
                        	 	a_idx,
                        	 	type,
                        	 	s_date,
                        	 	e_date,
                        	 	gubun,
                        	 	school_name,
                        	 	school_u_department,
                        	 	school_h_department,
                        	 	school_h_department_text,
                        	 	reg_date,
                        	 	up_date,
                        	 	gubun_text
                        	FROM
                        	 	WIV2_ACADEMIC_BACKGROUND
                        	WHERE a_idx = :a_idx ";
                $bindArray[':a_idx'] = $a_idx;

                $stmt = $db->prepare($sql);
                $stmt->execute($bindArray);
                $result = $stmt->fetchAll();

                $list = array();
                foreach ($result as $i => $row) {
                    $list[$i]['num'] = $i;
                    $list[$i]['idx'] = $row->idx;
                    $list[$i]['a_idx'] = $row->a_idx;
                    $list[$i]['type'] = $row->type;
                    $list[$i]['s_date'] = $row->s_date;
                    $list[$i]['e_date'] = $row->e_date;
                    $list[$i]['gubun'] = $row->gubun;
                    $list[$i]['school_name'] = $row->school_name;
                    $list[$i]['school_u_department'] = $row->school_u_department;
                    $list[$i]['school_h_department'] = $row->school_h_department;
                    $list[$i]['school_h_department_text'] = $row->school_h_department_text;
                    $list[$i]['reg_date'] = $row->reg_date;
                    $list[$i]['up_date'] = $row->up_date;
                    $list[$i]['gubun_text'] = $row->gubun_text;
                    if($row->type == "0"){
                        if($list[$i]['school_h_department'] != "4"){
                            switch($list[$i]['school_h_department']){
                                case 0 : $list[$i]['school_department']= '인문계' ;break;
                                case 1 : $list[$i]['school_department']= '실업계' ;break;
                                case 2 : $list[$i]['school_department']= '예체능' ;break;
                                case 3 : $list[$i]['school_department']= '특목고' ;break;
                            }
                            // $list[$i]['school_department'] = $list[$i]['school_h_department'];
                        }else{
                            $list[$i]['school_department'] = $list[$i]['school_h_department_text'];
                        }
                    }else{
                        $list[$i]['school_department'] = $list[$i]['school_u_department'];
                    }
                }
                return $list;

            }catch(Exception $e){
                echo $e;
                exit;
            }
        }

        function getInfo(){
            try{
                $db = new MyPDO();

                $bindArray = array();

                $sql = " SELECT A.ap_idx, A.ap_title, A.ap_info, A.info_file_1, A.info_file_2, A.info_file_3, A.s_date, A.e_date, A.reg_date, A.up_date, A.reset_pwd,
                            B.af_idx AS af_idx1, B.af_type AS af_type1, B.af_save_file_nm AS af_save_file_nm1, B.af_ori_file_nm AS af_ori_file_nm1, B.af_save_file_detail AS af_save_file_detail1, B.af_file_dir AS af_file_dir1,
                            C.af_idx AS af_idx2, C.af_type AS af_type2, C.af_save_file_nm AS af_save_file_nm2, C.af_ori_file_nm AS af_ori_file_nm2, C.af_save_file_detail AS af_save_file_detail2, C.af_file_dir AS af_file_dir2,
                            D.af_idx AS af_idx3, D.af_type AS af_type3, D.af_save_file_nm AS af_save_file_nm3, D.af_ori_file_nm AS af_ori_file_nm3, D.af_save_file_detail AS af_save_file_detail3, D.af_file_dir AS af_file_dir3
                         FROM WIV2_APPLY_PERIOD A
                            LEFT JOIN WIV2_APPLY_ATTACH_FILE B ON A.info_file_1 = B.af_idx
                            LEFT JOIN WIV2_APPLY_ATTACH_FILE C ON A.info_file_2 = C.af_idx
                            LEFT JOIN WIV2_APPLY_ATTACH_FILE D ON A.info_file_3 = D.af_idx
                         WHERE A.ap_Idx = 1
                         ORDER BY A.ap_idx ASC limit 1";

                $stmt = $db->prepare($sql);
                $stmt->execute($bindArray);
                $result = $stmt->fetch();

                $view = array();


                $view['ap_idx'] = $result->ap_idx;
                $view['ap_title'] = $result->ap_title;
                $view['reset_pwd'] = $result->reset_pwd;
                $view['info_file_1'] = $result->info_file_1;
                $view['info_file_2'] = $result->info_file_2;
                $view['info_file_3'] = $result->info_file_3;

                $view['s_date'] = $result->s_date;
                $view['e_date'] = $result->e_date;
                $view['reg_date'] = $result->reg_date;
                $view['up_date'] = $result->up_date;

                $view['af_idx1'] = $result->af_idx1;
                $view['af_save_file_nm1'] = $result->af_save_file_nm1;
                $view['af_ori_file_nm1'] =  $result->af_ori_file_nm1 == "" ? "파일선택" : $result->af_ori_file_nm1;
                $view['af_save_file_detail1'] = $result->af_save_file_detail1;
                $view['af_file_dir1'] = $result->af_file_dir1;

                $view['af_idx2'] = $result->af_idx2;
                $view['af_save_file_nm2'] = $result->af_save_file_nm2;
                $view['af_ori_file_nm2'] = $result->af_ori_file_nm2 == "" ? "파일선택" : $result->af_ori_file_nm2;
                $view['af_save_file_detail2'] = $result->af_save_file_detail2;
                $view['af_file_dir2'] = $result->af_file_dir2;

                $view['af_idx3'] = $result->af_idx3;
                $view['af_save_file_nm3'] = $result->af_save_file_nm3;
                $view['af_ori_file_nm3'] =  $result->af_ori_file_nm3 == "" ? "파일선택" : $result->af_ori_file_nm3;
                $view['af_save_file_detail3'] = $result->af_save_file_detail3;
                $view['af_file_dir3'] = $result->af_file_dir3;


                if($result->info_file_1 != ""){
                    $fileNm = $result->af_file_dir1 . '/' . $result->af_save_file_nm1;
                    $view['fileDownUrl1'] = $this->getViewUrl . '/fileDown.php?fileNm=' .$fileNm. '&oriFileNm='. $result->af_ori_file_nm1;
                }

                $view['fileDown2'] = $result->af_file_dir2.'/'.$result->af_save_file_nm2;
                $view['fileDownNm2'] = $result->af_ori_file_nm2;
                if($result->info_file_2 != ""){
                    $fileNm = $result->af_file_dir2 . '/' . $result->af_save_file_nm2;
                    $view['fileDownUrl2'] = $this->getViewUrl . '/fileDown.php?fileNm=' .$fileNm. '&oriFileNm='. $result->af_ori_file_nm2;
                }

                $view['fileDown3'] = $result->af_file_dir3.'/'.$result->af_save_file_nm3;
                $view['fileDownNm3'] = $result->af_ori_file_nm3;
                if($result->info_file_3 != ""){
                    $fileNm = $result->af_file_dir3 . '/' . $result->af_save_file_nm3;
                    $view['fileDownUrl3'] = $this->getViewUrl . '/fileDown.php?fileNm=' .$fileNm. '&oriFileNm='. $result->af_ori_file_nm3;
                }

                return $view;

            }catch(Exception $e){
                echo $e;
                exit;
            }
        }



    /**
    *    ***************************************************************
    *    SET & UPDATE
    *    ***************************************************************
    **/

        function InStep1(){
            $a1_name = $_POST["a1_name"];
            $a1_gubun = $_POST["a1_gubun"];
            $a1_birth = $_POST["a1_birth"];
            $a1_gender = $_POST["a1_gender"];
            $a1_tel = $_POST["a1_tel"];
            $a1_phone = $_POST["a1_phone"];
            $a1_email = $_POST["a1_email"];
            $a1_pwd = $_POST["a1_pwd"];
            try{
                $db = new MyPDO();
                $aesKey = $db->getAES_KEY();
                try{
                    $db->beginTransaction();
                    $bindArray = array();
                    $sql = " INSERT INTO WIV2_APPLICANT SET
            		 			ai_idx  = '1'
            		 			,a1_gubun = :a1_gubun
            		 			,a1_name = HEX(AES_ENCRYPT(:a1_name,'$aesKey'))
            		 			,a1_tel = HEX(AES_ENCRYPT(:a1_tel,'$aesKey'))
            		 			,a1_phone = HEX(AES_ENCRYPT(:a1_phone,'$aesKey'))
            		 			,a1_gender = :a1_gender
            		 			,a1_birth = HEX(AES_ENCRYPT(:a1_birth,'$aesKey'))
            		 			,a1_email = HEX(AES_ENCRYPT(:a1_email,'$aesKey'))
            		 			,a1_pwd = SHA(:a1_pwd)
            		 			,a1_reg_date = NOW() ";

                    $bindArray[':a1_gubun'] = $a1_gubun;
                    $bindArray[':a1_name'] = $a1_name;
                    $bindArray[':a1_tel'] = $a1_tel;
                    $bindArray[':a1_phone'] = $a1_phone;
                    $bindArray[':a1_gender'] = $a1_gender;
                    $bindArray[':a1_birth'] = $a1_birth;
                    $bindArray[':a1_email'] = $a1_email;
                    $bindArray[':a1_pwd'] = $a1_pwd;

                    $stmt = $db->prepare($sql);
                    $stmt->execute($bindArray);
                    $a_idx = $db->lastInsertId();
                    $db->commit();
                    return $a_idx;
                }catch(PDOExecption $e){
                    $db->rollback();
                    throw $e;
                }
            }catch(Exception $e){
                exit($e->getMessage());
            }
        }
        function UpStep1(){
            $a_idx = $_POST["a_idx"];
        	$a1_name = $_POST["a1_name"];
        	$a1_gubun = $_POST["a1_gubun"];
        	$a1_birth = $_POST["a1_birth"];
        	$a1_gender = $_POST["a1_gender"];
        	$a1_tel = $_POST["a1_tel"];
        	$a1_phone = $_POST["a1_phone"];
        	$a1_email = $_POST["a1_email"];
        	$a1_pwd = $_POST["a1_pwd"];
            try{
                $db = new MyPDO();
                $aesKey = $db->getAES_KEY();
                try{
                    $db->beginTransaction();
                    $bindArray = array();
                    $sql = "     UPDATE WIV2_APPLICANT SET
                    	 			a1_gubun = :a1_gubun
                    	 			,a1_name = HEX(AES_ENCRYPT(:a1_name,'$aesKey'))
                    	 			,a1_tel = HEX(AES_ENCRYPT(:a1_tel,'$aesKey'))
                    	 			,a1_phone = HEX(AES_ENCRYPT(:a1_phone,'$aesKey'))
                    	 			,a1_gender = :a1_gender
                    	 			,a1_birth = HEX(AES_ENCRYPT(:a1_birth,'$aesKey')) ";
                	if($a1_pwd != ""){
                		$sql .= " 	,a1_pwd = SHA(:a1_pwd) ";
                        $bindArray[':a1_pwd'] = $a1_pwd;
                	}
                	$sql .= " 			,a1_up_date = NOW()
                                 WHERE a_idx = :a_idx  ";

                    $bindArray[':a1_gubun'] = $a1_gubun;
                    $bindArray[':a1_name'] = $a1_name;
                    $bindArray[':a1_tel'] = $a1_tel;
                    $bindArray[':a1_phone'] = $a1_phone;
                    $bindArray[':a1_gender'] = $a1_gender;
                    $bindArray[':a1_birth'] = $a1_birth;
                    $bindArray[':a_idx'] = $a_idx;

                    $stmt = $db->prepare($sql);
                    $stmt->execute($bindArray);

                    $db->commit();
                    return $a_idx;
                }catch(PDOExecption $e){
                    $db->rollback();
                    throw $e;
                }
            }catch(Exception $e){
                exit($e->getMessage());
            }
        }
        function UpStep2(){
            $mode = $_POST["mode"];                                             //mode
            $a_idx = $_POST["a_idx"];
            $state = $_POST["state"];

            $a2_addr = $_POST["a2_addr"];
            $a2_addr_detail = $_POST["a2_addr_detail"];
            $a2_school_type = $_POST["a2_school_type"];
            $a2_school_name = $_POST["a2_school_name"];
            $a2_school_h_department = $_POST["a2_school_h_department"];
            $a2_school_h_department_text = $_POST["a2_school_h_department_text"];
            $a2_school_u_department = $_POST["a2_school_u_department"];
            $a2_school_year = $_POST["a2_school_year"];
            $a2_school_semester = $_POST["a2_school_semester"];
            $a2_school_gubun = $_POST["a2_school_gubun"];
            $a2_school_gubun_text = $_POST["a2_school_gubun_text"];
            $a2_school_add_text = $_POST["a2_school_add_text"];
            $a2_ab_add_text = $_POST["a2_ab_add_text"];
            $a2_bank_gubun = $_POST["a2_bank_gubun"];
            $a2_bank_gubun_text = $_POST["a2_bank_gubun_text"];
            $a2_bank_name = $_POST["a2_bank_name"];
            $a2_bank_account = $_POST["a2_bank_account"];
            $a2_bank_account_holder = $_POST["a2_bank_account_holder"];
            $a2_protector_gubun = $_POST["a2_protector_gubun"];
            $a2_protector_gubun_text = $_POST["a2_protector_gubun_text"];
            $a2_protector_name = $_POST["a2_protector_name"];
            $a2_protector_tel_01 = $_POST["a2_protector_tel_01"];
            $a2_protector_tel_02 = $_POST["a2_protector_tel_02"];
            $a2_protector_tel_03 = $_POST["a2_protector_tel_03"];
            $a2_protector_tel = $a2_protector_tel_01 . '-' . $a2_protector_tel_02 . '-' .$a2_protector_tel_03;
            $a2_reg_date = $_POST["a2_reg_date"];
            $a2_up_date = $_POST["a2_up_date"];
            $a2_up_state = $_POST["a2_up_state"];
            // array -------
            $ab_type = $_POST["ab_type"];
            $ab_s_date = $_POST["ab_s_date"];
            $ab_e_date = $_POST["ab_e_date"];
            $ab_gubun = $_POST["ab_gubun"];
            $ab_school_name = $_POST["ab_school_name"];
            $ab_school_u_department = $_POST["ab_school_u_department"];
            $ab_gubun_text = $_POST["ab_gubun_text"];
            $ab_school_h_department = $_POST["ab_school_h_department"];
            $ab_school_h_department_text = $_POST["ab_school_h_department_text"];
            // array -------
            try{
                $db = new MyPDO();
                $aesKey = $db->getAES_KEY();
                try{
                    $db->beginTransaction();
                    $bindArray = array();
                    $sql = "     UPDATE WIV2_APPLICANT SET
                                    a2_addr = HEX(AES_ENCRYPT(:a2_addr,'$aesKey'))
                                   ,a2_addr_detail = HEX(AES_ENCRYPT(:a2_addr_detail,'$aesKey'))
                                   ,a2_school_type = :a2_school_type
                                   ,a2_school_name = :a2_school_name
                                   ,a2_school_h_department = :a2_school_h_department
                                   ,a2_school_h_department_text = :a2_school_h_department_text
                                   ,a2_school_u_department = :a2_school_u_department
                                   ,a2_school_year = :a2_school_year
                                   ,a2_school_semester = :a2_school_semester
                                   ,a2_school_gubun = :a2_school_gubun
                                   ,a2_school_gubun_text = :a2_school_gubun_text
                                   ,a2_school_add_text = :a2_school_add_text
                                   ,a2_ab_add_text = :a2_ab_add_text
                                   ,a2_bank_gubun = :a2_bank_gubun
                                   ,a2_bank_gubun_text = :a2_bank_gubun_text
                                   ,a2_bank_name = HEX(AES_ENCRYPT(:a2_bank_name,'$aesKey'))
                                   ,a2_bank_account = HEX(AES_ENCRYPT(:a2_bank_account,'$aesKey'))
                                   ,a2_bank_account_holder = HEX(AES_ENCRYPT(:a2_bank_account_holder,'$aesKey'))
                                   ,a2_protector_gubun = :a2_protector_gubun
                                   ,a2_protector_gubun_text = :a2_protector_gubun_text
                                   ,a2_protector_name = HEX(AES_ENCRYPT(:a2_protector_name,'$aesKey'))
                                   ,a2_protector_tel = HEX(AES_ENCRYPT(:a2_protector_tel,'$aesKey')) ";
                    // if($state == "next" && $a2_reg_date == ""){
					if($state == "next"){
						if($a2_reg_date == ""){
						   $sql .= "       ,a2_reg_date = NOW() ";
						}else{
						   $sql .= "       ,a2_up_date = NOW() ";
						   $sql .= "       ,a2_up_state = 'N' ";
						}
					}
                    $sql .= " WHERE a_idx = :a_idx ";

                    $bindArray[':a2_addr'] = $a2_addr;
                    $bindArray[':a2_addr_detail'] = $a2_addr_detail;
                    $bindArray[':a2_school_type'] = $a2_school_type;
                    $bindArray[':a2_school_name'] = $a2_school_name;
                    $bindArray[':a2_school_h_department'] = $a2_school_h_department;
                    $bindArray[':a2_school_h_department_text'] = $a2_school_h_department_text;
                    $bindArray[':a2_school_u_department'] = $a2_school_u_department;
                    $bindArray[':a2_school_year'] = $a2_school_year;
                    $bindArray[':a2_school_semester'] = $a2_school_semester;
                    $bindArray[':a2_school_gubun'] = $a2_school_gubun;
                    $bindArray[':a2_school_gubun_text'] = $a2_school_gubun_text;
                    $bindArray[':a2_school_add_text'] = $a2_school_add_text;
                    $bindArray[':a2_ab_add_text'] = $a2_ab_add_text;
                    $bindArray[':a2_bank_gubun'] = $a2_bank_gubun;
                    $bindArray[':a2_bank_gubun_text'] = $a2_bank_gubun_text;
                    $bindArray[':a2_bank_name'] = $a2_bank_name;
                    $bindArray[':a2_bank_account'] = $a2_bank_account;
                    $bindArray[':a2_bank_account_holder'] = $a2_bank_account_holder;
                    $bindArray[':a2_protector_gubun'] = $a2_protector_gubun;
                    $bindArray[':a2_protector_gubun_text'] = $a2_protector_gubun_text;
                    $bindArray[':a2_protector_name'] = $a2_protector_name;
                    $bindArray[':a2_protector_tel'] = $a2_protector_tel;
                    $bindArray[':a_idx'] = $a_idx;

                    $stmt = $db->prepare($sql);
                    $stmt->execute($bindArray);


                    $bindArray = array();
                    $sql = "     DELETE FROM WIV2_ACADEMIC_BACKGROUND WHERE a_idx = :a_idx ";
                    $bindArray[':a_idx'] = $a_idx;
                    $stmt = $db->prepare($sql);
                    $stmt->execute($bindArray);

                    for($i=0; $i < count($ab_type) ; $i++) {
                        $bindArray = array();
                        $sql = "     INSERT INTO WIV2_ACADEMIC_BACKGROUND SET
                                        a_idx = :a_idx
                                        ,type = :ab_type
                                        ,s_date = :ab_s_date
                                        ,e_date = :ab_e_date
                                        ,gubun = :ab_gubun
                                        ,school_name = :ab_school_name
                                        ,school_u_department = :ab_school_u_department
                                        ,gubun_text = :ab_gubun_text
                                        ,school_h_department = :ab_school_h_department
                                        ,school_h_department_text = :ab_school_h_department_text
                                        ,reg_date = NOW() ";
                        $bindArray[':a_idx'] = $a_idx;
                        $bindArray[':ab_type'] = $ab_type[$i];
                        $bindArray[':ab_s_date'] = $ab_s_date[$i];
                        $bindArray[':ab_e_date'] = $ab_e_date[$i];
                        $bindArray[':ab_gubun'] = $ab_gubun[$i];
                        $bindArray[':ab_school_name'] = $ab_school_name[$i];
                        $bindArray[':ab_school_u_department'] = $ab_school_u_department[$i];
                        $bindArray[':ab_gubun_text'] = $ab_gubun_text[$i];
                        $bindArray[':ab_school_h_department'] = $ab_school_h_department[$i];
                        $bindArray[':ab_school_h_department_text'] = $ab_school_h_department_text[$i];
                        $stmt = $db->prepare($sql);
                        $stmt->execute($bindArray);
                    }

                    $db->commit();
                    return true;
                }catch(PDOExecption $e){
                    $db->rollback();
                    throw $e;
                }
            }catch(Exception $e){
                exit($e->getMessage());
            }
        }
        function UpStep3(){
            $mode = $_POST["mode"];
            $a_idx = $_POST["a_idx"];
            $state = $_POST["state"];
            $a3_self_introduction_1 = $_POST["a3_self_introduction_1"];
            $a3_self_introduction_2 = $_POST["a3_self_introduction_2"];
            $a3_self_introduction_3 = $_POST["a3_self_introduction_3"];
            $a3_self_introduction_4 = $_POST["a3_self_introduction_4"];
            $a3_reg_date = $_POST["a3_reg_date"];

            try{
                $db = new MyPDO();
                $aesKey = $db->getAES_KEY();
                try{
                    $db->beginTransaction();
                    $bindArray = array();
                    $sql = "     UPDATE WIV2_APPLICANT SET
                                    a3_self_introduction_1 = :a3_self_introduction_1
                                    ,a3_self_introduction_2 = :a3_self_introduction_2
                                    ,a3_self_introduction_3 = :a3_self_introduction_3
                                    ,a3_self_introduction_4 = :a3_self_introduction_4 ";
                    // if($state == "next" && $a3_reg_date == ""){
					if($state == "next"){
						if($a3_reg_date == ""){
							$sql .= "       ,a3_reg_date = NOW() ";
						}else{
							$sql .= "       ,a3_up_date = NOW() ";
							$sql .= "       ,a3_up_state = 'N' ";
						}
					}
                    $sql .= " WHERE a_idx = :a_idx ";

                    $bindArray[':a3_self_introduction_1'] = $a3_self_introduction_1;
                    $bindArray[':a3_self_introduction_2'] = $a3_self_introduction_2;
                    $bindArray[':a3_self_introduction_3'] = $a3_self_introduction_3;
                    $bindArray[':a3_self_introduction_4'] = $a3_self_introduction_4;
                    $bindArray[':a_idx'] = $a_idx;

                    $stmt = $db->prepare($sql);
                    $stmt->execute($bindArray);

                    $db->commit();
                    return true;
                }catch(PDOExecption $e){
                    $db->rollback();
                    throw $e;
                }
            }catch(Exception $e){
                exit($e->getMessage());
            }
        }
        function UpStep4(){
            $mode = $_POST["mode"];
            $a_idx = $_POST["a_idx"];
            $state = $_POST["state"];

            $file_name_01 = $_POST["file_name_01"];
            $file_name_02 = $_POST["file_name_02"];
            $file_name_03 = $_POST["file_name_03"];
            $a4_file_1 = $_POST["a4_file_1"];
            $a4_file_2 = $_POST["a4_file_2"];
            $a4_file_3 = $_POST["a4_file_3"];
            $app_file_01 = $_POST["app_file_01"];
            $app_file_02 = $_POST["app_file_02"];
            $app_file_03 = $_POST["app_file_03"];

            $a4_file_3_gubun = $_POST["a4_file_3_gubun"];
            $a4_agree_1 = $_POST["a4_agree_1"];
            $a4_agree_2 = $_POST["a4_agree_2"];
            $a4_reg_date = $_POST["a4_reg_date"];
// var_dump($_POST);var_dump($_FILES);exit;
            try{
                $FileUpload = new FileUpload($this->applyUploadDir);
                $db = new MyPDO();
                $aesKey = $db->getAES_KEY();
                try{
                    $db->beginTransaction();

                    if($app_file_01 != "" && $a4_file_1 != ""){
                        $delFileList1 = $this->fileDelete($a4_file_1);
                    }
                    if($app_file_02 != "" && $a4_file_2 != ""){
                        $delFileList2 = $this->fileDelete($a4_file_2);
                    }
                    if($app_file_03 != "" && $a4_file_3 != ""){
                        $delFileList3 = $this->fileDelete($a4_file_3);
                    }

                    if($app_file_01 != ""){
                        $FileUpload->define($_FILES[app_file_01]);
                        $saveNameArray1 = $FileUpload->uploadedFiles();
                        $fileIdx1 = "0";
                        foreach($saveNameArray1 as $i => $row){
                            $fileIdx1 = $this->fileInsert($row, $FileUpload->upload_directory, $FileUpload->upload_subdirectory);
                        }
                    }else{
                        $fileIdx1 = $a4_file_1;
                    }

                    if($app_file_02 != ""){
                        $FileUpload->define($_FILES[app_file_02]);
                        $saveNameArray2 = $FileUpload->uploadedFiles();
                        $fileIdx2 = "0";
                        foreach($saveNameArray2 as $i => $row){
                            $fileIdx2 = $this->fileInsert($row, $FileUpload->upload_directory, $FileUpload->upload_subdirectory);
                        }
                    }else{
                        $fileIdx2 = $a4_file_2;
                    }

                    if($app_file_03 != ""){
                        $FileUpload->define($_FILES[app_file_03]);
                        $saveNameArray3 = $FileUpload->uploadedFiles();
                        $fileIdx3 = "0";
                        foreach($saveNameArray3 as $i => $row){
                            $fileIdx3 = $this->fileInsert($row, $FileUpload->upload_directory, $FileUpload->upload_subdirectory);
                        }
                    }else{
                        $fileIdx3 = $a4_file_3;
                    }

                    $bindArray = array();
                    $sql = "     UPDATE WIV2_APPLICANT SET
                                    a4_agree_1 = :a4_agree_1
                                    ,a4_agree_2 = :a4_agree_2
                                    ,a4_file_1 = :fileIdx1
                                    ,a4_file_2 = :fileIdx2
                                    ,a4_file_3 = :fileIdx3
                                    ,a4_file_3_gubun = :a4_file_3_gubun ";
					if($state == "next"){
						if($a4_reg_date == ""){
							$sql .= "       ,a4_reg_date = NOW() ";
						}else{
							$sql .= "       ,a4_up_date = NOW() ";
							$sql .= "       ,a4_up_state = 'N' ";
						}
					}
                    $sql .= " WHERE a_idx = :a_idx ";

                    $bindArray[':a4_agree_1'] = $a4_agree_1;
                    $bindArray[':a4_agree_2'] = $a4_agree_2;
                    $bindArray[':fileIdx1'] = $fileIdx1;
                    $bindArray[':fileIdx2'] = $fileIdx2;
                    $bindArray[':fileIdx3'] = $fileIdx3;
                    $bindArray[':a4_file_3_gubun'] = $a4_file_3_gubun;
                    $bindArray[':a_idx'] = $a_idx;

                    $stmt = $db->prepare($sql);
                    $stmt->execute($bindArray);
                    if(!empty($delFileList1)){
                        foreach($delFileList1 as $i => $row){
                            unlink($row['delFile']);
                        }
                    }
                    if(!empty($delFileList2)){
                        foreach($delFileList2 as $i => $row){
                            unlink($row['delFile']);
                        }
                    }
                    if(!empty($delFileList3)){
                        foreach($delFileList3 as $i => $row){
                            unlink($row['delFile']);
                        }
                    }
                    $db->commit();
                    return true;
                }catch(PDOExecption $e){
                    $db->rollback();
                    throw $e;
                }
            }catch(Exception $e){
                exit($e->getMessage());
            }
        }

        function sendEmailJoin(){
            $a_idx = $_POST["a_idx"];
            try{
                $db = new MyPDO();
                $aesKey = $db->getAES_KEY();

                $bindArray = array();
                $sql = "    SELECT
                                AES_DECRYPT(UNHEX(a1_name),'$aesKey') AS a1_name
                                , AES_DECRYPT(UNHEX(a1_email),'$aesKey') AS a1_email
                            FROM WIV2_APPLICANT
                            WHERE a_idx = :a_idx ";
                $bindArray[':a_idx'] = $a_idx;
                $stmt = $db->prepare($sql);
                $stmt->execute($bindArray);
                $result = $stmt->fetch();
                $name = $result->a1_name;
                $email = $result->a1_email;

                $nameFrom  = "우인장학재단";
                $mailFrom = "wooin@wooin.org";

                //받는 사람
                $nameTo  = "$name";
                $mailTo = "$email";

                $cc = "";						//참조
                //    $bcc = "wooin@wooin.org";						//숨은 참조
                $bcc = "";						//숨은 참조

                $subject = "우인장학재단 온라인 신청 접수 완료";		//제목

                $content = '<img src="http://wooin.org/v2/email/images/img_application_complete.gif" alt="온라인 신청" />';

                $charset = "UTF-8";

                $nameFrom   = "=?$charset?B?".base64_encode($nameFrom)."?=";
                $nameTo   = "=?$charset?B?".base64_encode($nameTo)."?=";
                $subject = "=?$charset?B?".base64_encode($subject)."?=";

                $header  = "Content-Type: text/html; charset=utf-8\r\n";
                $header .= "MIME-Version: 1.0\r\n";
                $header .= "Return-Path: <". $mailFrom .">\r\n";
                $header .= "From: ". $nameFrom ." <". $mailFrom .">\r\n";
                $header .= "Reply-To: <". $mailFrom .">\r\n";
                if ($cc)  $header .= "Cc: ". $cc ."\r\n";
                if ($bcc) $header .= "Bcc: ". $bcc ."\r\n";

                $resultMail = mail($mailTo, $subject, $content, $header, '-f'.$mailFrom);

                return $resultMail;

            }catch(Exception $e){
                echo $e;
                exit;
            }
        }


        function fileInsert($saveNameArray, $upload_directory, $upload_subdirectory){
            try{
                $db = new MyPDO();

                $bindArray = array();
                $sql = "INSERT INTO WIV2_APPLY_ATTACH_FILE(af_type, af_ori_file_nm, af_save_file_nm, af_file_dir, af_save_file_detail)
                                                    VALUES(:af_type, :oriFileNm, :saveFileNm, :upSubDir, :upDir)
                                                    ";
                $bindArray[':af_type'] = 1;
                $bindArray[':oriFileNm'] = $saveNameArray['orifileNm'];
                $bindArray[':saveFileNm'] = $saveNameArray['saveFileNm'];
                $bindArray[':upSubDir'] = "/apply/".$upload_subdirectory;
                $bindArray[':upDir'] = $upload_directory."/".$upload_subdirectory."/";

                $stmt = $db->prepare($sql);
                $stmt->execute($bindArray);

                $af_idx = $db->lastInsertId();

                return $af_idx;

            }catch(Exception $e){
                exit($e->getMessage());
            }
        }

        function fileDelete($af_idx,$notDelFileIdx = ""){
            try{
                $db = new MyPDO();

                $bindArray = array();
                $sql = "SELECT af_idx, af_type, af_ori_file_nm, af_save_file_nm, af_file_dir, af_save_file_detail
                        FROM WIV2_APPLY_ATTACH_FILE
                        WHERE af_idx = :af_idx
                        ";
                if($notDelFileIdx != ""){
                    $sql .= " AND qf_idx NOT IN ($notDelFileIdx) ";
                }
                $bindArray[':af_idx'] = $af_idx;
                // $bindArray[':notDelFileIdx'] = $notDelFileIdx;

                $stmt = $db->prepare($sql);
                $stmt->execute($bindArray);
                $result = $stmt->fetchAll();

                $list = array();
                foreach ($result as $i => $row) {
                    $list[$i]['delFile'] = $row->af_save_file_detail.$row->af_save_file_nm;

                    $bindArray = array();
                    $sqlFileDel = "DELETE FROM WIV2_APPLY_ATTACH_FILE WHERE af_idx = :fileIdx ";
                    $bindArray[':fileIdx'] = $af_idx;
                    $stmt = $db->prepare($sqlFileDel);
                    $stmt->execute($bindArray);
                }
                return $list;
            }catch(Exception $e){
                exit($e->getMessage());
            }
        }

        function delApply(){
            $a_idx = $_POST[a_idx];
            try{
                $db = new MyPDO();
                $aesKey = $db->getAES_KEY();
                try{
                    $db->beginTransaction();
                    $bindArray = array();
                    $sql = "     DELETE FROM WIV2_APPLICANT WHERE a_idx = :a_idx  ";

                    $bindArray[':a_idx'] = $a_idx;

                    $stmt = $db->prepare($sql);
                    $stmt->execute($bindArray);

                    $db->commit();
                    return true;
                }catch(PDOExecption $e){
                    $db->rollback();
                    throw $e;
                }
            }catch(Exception $e){
                exit($e->getMessage());
            }
        }


    }
?>
