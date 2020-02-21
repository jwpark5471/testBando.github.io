<?
	require_once '../../classes/Config.php';
	use classes\apply\Apply as Apply;

    $step = $_POST['step'];
    $mode = $_POST['mode'];

	$Apply = new Apply();

    switch($mode){
        case 'in' :
                        if($step == '1'){
                            $checkCnt = $Apply->emailCheck();
                            if($checkCnt > 0){
                                $modeResult->result = 0;
                                $modeResult->msg = '이미 등록된 이메일입니다.';
                                $modeResult = json_encode($modeResult);
                                echo $modeResult;
                                break;
                            }

                            $a_idx = $Apply->InStep1();
                            $succMsg = '등록에 성공하였습니다.';
                            $failMsg = '등록에 실패하였습니다. 다시 시도해주세요.';
                        }

                        $modeResult = new stdClass();
                        if($a_idx != ""){
                            $modeResult->result = 1;
                            $modeResult->a_idx = $a_idx;
                            $modeResult->msg = $succMsg;
                        }else{
                            $modeResult->result = 0;
                            $modeResult->msg = $failMsg;
                        }
                        $modeResult = json_encode($modeResult);
                        echo $modeResult;
                        break;
        case 'up' :
                        if($step == '1'){
                            $a_idx = $Apply->UpStep1();
                        }else if($step == '2'){
                            $a_idx = $Apply->UpStep2();
                        }else if($step == '3'){
                            $a_idx = $Apply->UpStep3();
                        }else if($step == '4'){
                            $a_idx = $Apply->UpStep4();
                            if($a_idx && $_POST["state"] == 'next'){
                                $Apply->sendEmailJoin();
                            }
                        }


                        $succMsg = '수정에 성공하였습니다.';
                        $failMsg = '수정에 실패하였습니다. 다시 시도해주세요.';

                        $modeResult = new stdClass();
                        if($a_idx){
                            $modeResult->result = 1;
                            $modeResult->a_idx = $a_idx;
                            $modeResult->state = $_POST[state];
                            $modeResult->msg = $succMsg;
                        }else{
                            $modeResult->result = 0;
                            $modeResult->msg = $failMsg;
                        }
                        $modeResult = json_encode($modeResult);
                        echo $modeResult;
                        break;
        case 'login'   :
                        $a_idx = $Apply->login();

                        $modeResult = new stdClass();
                        if($a_idx != ""){
                		    $_SESSION[session_a_idx] = $a_idx;

                            $modeResult->result = 1;
		                    $modeResult->a_idx = $a_idx;
                            $modeResult->msg = $succMsg;
                        }else{
                            $modeResult->result = 0;
                            $modeResult->msg = '아이디(이메일) 과 비밀번호를 다시 확인해주세요.';
                        }
                        $modeResult = json_encode($modeResult);
                        echo $modeResult;
                        break;
        case 'searchPwd'   :
                            $result = $Apply->searchPwd();

                            if($result[a_idx] != ""){
                                $sendResult = $Apply->sendPwdEmail($result);

                                $modeResult = new stdClass();
                                if($sendResult){
                                    $modeResult->result = 1;
                                    $modeResult->msg = '인증 메일 전송 완료했습니다.';
                                }else{
                                    $modeResult->result = 0;
                                    $modeResult->msg = '인증 메일 전송에 실패했습니다. 다시 시도해주세요.';
                                }
                            }else{
                                $modeResult->result = 0;
                                $modeResult->msg = '이메일, 휴대번호를 확인해주세요.';
                            }
                            $modeResult = json_encode($modeResult);
                            echo $modeResult;
                            break;
        case 'emailAuth'   :
                            $checkCnt = $Apply->emailCheck();

                            if($checkCnt < 1){
                                $authReulst = $Apply->emailAuth();
                                if($authReulst != ""){
                                    $sendReulst = $Apply->sendEmailAuth($authReulst);
                                    if($sendReulst){
                                        $modeResult->result = 1;
                                        $modeResult->msg = '전송성공';
                                    }else{
                                        $modeResult->result = 0;
                                        $modeResult->msg = '인증 메일 전송에 실패했습니다. 다시 시도해주세요.';
                                    }
                                }else{
                                    $modeResult->result = 0;
                                    $modeResult->msg = '이메일 인증과정에 실패했습니다. 다시 시도해주세요';
                                }
                            }else{
                                $modeResult->result = 0;
                                $modeResult->msg = '이미 등록된 이메일입니다.';
                            }
                            $modeResult = json_encode($modeResult);
                            echo $modeResult;
                            break;
        case 'emailAuthCode'   :
                            $result = $Apply->emailAuthCode();
                            if($result){
                                $modeResult->result = 1;
                                $modeResult->msg = '인증성공';
                            }else{
                                $modeResult->result = 0;
                                $modeResult->msg = '인증번호를 확인해주세요.';
                            }
                            $modeResult = json_encode($modeResult);
                            echo $modeResult;
                            break;
        case 'del'   :
                            $result = $Apply->delApply();

                            $succMsg = '삭제에 성공하였습니다.';
                            $failMsg = '삭제에 실패하였습니다. 다시 시도해주세요.';

                            $modeResult = new stdClass();
                            if($result){
                                $modeResult->result = 1;
                                $modeResult->msg = $succMsg;
                            }else{
                                $modeResult->result = 0;
                                $modeResult->msg = $failMsg;
                            }
                            $modeResult = json_encode($modeResult);
                            echo $modeResult;
                            break;
        default :
                    echo "잘못된 접근";
    }
?>
