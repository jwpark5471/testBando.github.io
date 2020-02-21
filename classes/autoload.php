<?php
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
        $base_dir = $_SERVER[DOCUMENT_ROOT].'/v2/';
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

 ?>
