<?php
    //******************* 설정 *******************
    //에러메시지 출력
    ini_set('display_errors', 1);
    ini_set('error_reporting', E_ALL ^ E_NOTICE);

    //******************* 전역 변수 *******************

    // view페이지 URL
    // $getViewUrl = $_SERVER[DOCUMENT_ROOT].'/v2/view';
    $getViewUrl = '/v2/view';
    $getViewPath = $_SERVER[DOCUMENT_ROOT].'/view';

    //업로드 경로
    $uploadDir = $_SERVER[DOCUMENT_ROOT].'/@upload';
    //게시판 파일 업로드 경로
    $boardUploadDir = $_SERVER[DOCUMENT_ROOT].'/@upload/board';
    //지원신청서 파일 업로드 경로
    $applyUploadDir = $_SERVER[DOCUMENT_ROOT].'/@upload/apply';

    $headTitle = "2020 미세먼지 EXPO";

    //******************* function *******************



    //******************* 오토 로드 *******************

    // spl_autoload_register(function ($class){
    //     $class = str_replace('\\','/',$class);
    //     $class = $_SERVER[DOCUMENT_ROOT].'/v2/class/'.$class.'.php';
    //     // $class = $class.'.php';
    //     // print("path : {$class}");
    //     require_once $class;
    // });
    spl_autoload_register(function ($class) {

        // 프로젝트에 따른 네임스페이스 프리픽스
        $prefix = '';
        // 네임스페이스 프리픽스를 위한 기본 디렉토리 경로
        // $base_dir = __DIR__ . '/src/';
        $base_dir = $_SERVER[DOCUMENT_ROOT].'/';
        // 네임스페이스 프리픽스에 해당하는지 검사
        $len = strlen($prefix);
        if (strncmp($prefix, $class, $len) !== 0) {
            // 찾지 못했으므로 반환. 만약 다른 오토로드 함수가 등록되어 있다면 순차적으로 실행함.
            return;
        }
        // 네임스페이스를 제외한 상대 클래스명 찾기
        $relative_class = substr($class, $len);
        // 네임스페이스 프리픽스를 기본 디렉토리 경로로 치환, 네임스페이스 구분자를 디렉토리 구분자로
        // 전환하고 .php를 끝에 추가함
        $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
        // 파일이 존재하면 불러옴
        if (file_exists($file)) {
            require $file;
        }
    });

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


    session_start();


 ?>
