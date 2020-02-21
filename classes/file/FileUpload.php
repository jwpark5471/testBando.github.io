<?php
    namespace classes\file;
    use classes\Common as Common;
    class FileUpload extends Common{
        /*
        *   파일업로드 class
        *   https://phpschool.com/gnuboard4/bbs/board.php?bo_table=tipntech&wr_id=37265&sca=&sfl=wr_subject&stx=%B4%D9%C1%DF&sop=and
        */

        public $upload_tmpFileName = null;
        public $upload_fileName = null;
        public $upload_fileSize = null;
        public $upload_fileType = null;
        public $upload_fileWidth = null;
        public $upload_fileHeight = null;
        public $upload_fileExt = null;
        public $upload_fileImageType = null;
        public $upload_count = 0;
        public $upload_directory = null;
        public $upload_subdirectory = null;
        public $denied_ext = array("php" => ".phps"
                                    , "phtml" => ".phps"
                                    , "html" => ".txt"
                                    , "htm" => ".txt"
                                    , "inc" => ".txt"
                                    , "sql" => ".txt"
                                    , "cgi" => ".txt"
                                    , "pl" => ".txt"
                                    , "jsp" => ".txt"
                                    , "asp" => ".txt"
                                    , "phtm" => ".txt");

        public function __construct($upload_dir) {
            $this -> upload_directory = $upload_dir;
            // $this -> upload_subdirectory = time();
            $this -> upload_subdirectory = date('Y').'/'.date('m');
        }
        public function define($files) {
            if (is_array($files['tmp_name'])) {
                $fileNameNum = 0;
                for ($i = 0; $i < sizeof($files['tmp_name']); $i ++) {
                    $this -> _uploadErrorChecks($files['error'][$i], $files['name'][$i]);
                    if (is_uploaded_file($files['tmp_name'][$i])) {
                        $this -> upload_tmpFileName[$fileNameNum] = $files['tmp_name'][$i];
                        $this -> upload_fileName[$fileNameNum] = $this -> _emptyToUnderline($this -> _checkFileName($files['name'][$i]));
                        $this -> upload_fileSize[$fileNameNum] = (int)$files['size'][$i]
                            ? $files['size'][$i]
                            : 0;
                        $this -> upload_fileType[$fileNameNum] = $files['type'][$i];
                        $this -> upload_fileExt[$fileNameNum] = $this -> _getExtension($files['name'][$i]);
                        // 이미지 파일
                        if ($this -> _isThisImageFile($files['type'][$i]) == true) {
                            $img = $this -> _getImageSize($files['tmp_name'][$i]);
                            $this -> upload_fileWidth[$fileNameNum] = (int)$img[0];
                            $this -> upload_fileHeight[$fileNameNum] = (int)$img[1];
                            $this -> upload_fileImageType[$fileNameNumi] = $img[2];
                        }
                        $fileNameNum++;
                    }
                }
            } else {
                $this -> _uploadErrorChecks($files['error'], $files['name']);
                if (is_uploaded_file($files['tmp_name'])) {
                    $this -> upload_tmpFileName = $files['tmp_name'];
                    $this -> upload_fileName = $this -> _emptyToUnderline($this -> _checkFileName($files['name']));
                    $this -> upload_fileSize = (int)($files['size'])
                        ? $files['size']
                        : 0;
                    $this -> upload_fileType = $files['type'];
                    $this -> upload_fileExt = $this -> _getExtension($files['name']);
                    // 이미지 파일
                    if ($this -> _isThisImageFile($files['type']) == true) {
                        $img = $this -> _getImageSize($files['tmp_name']);
                        $this -> upload_fileWidth = $img[0];
                        $this -> upload_fileHeight = $img[1];
                        $this -> upload_fileImageType = $img[2];
                    }
                }
            }
        }
        private function _isThisImageFile($type) {
            if (preg_match("/image/i", $type) == false && preg_match("/flash/", $type) == false) {
                return false;
            } else {
                return true;
            }
        }
        private function _uploadErrorChecks($errorCode, $fileName) {
            if ($errorCode == UPLOAD_ERR_INI_SIZE) {
                throw new Exception($fileName
                    ." : 업로드 제한용량("
                    .ini_get('upload_max_filesize')
                    .")을 초과한 파일입니다.");
            } else if ($errorCode == UPLOAD_ERR_FORM_SIZE) {
                throw new Exception($fileName." : 업로드한 파일이 HTML 에서 정의되어진 파일 업로드 제한용량을 초과하였습니다.");
            } else if ($errorCode == UPLOAD_ERR_PARTIAL) {
                throw new Exception("파일이 일부분만 전송되었습니다. ");
            }
        }
        private function _mkUploadDir() {
            if (is_dir($this -> upload_directory) == false) {
                if (@mkdir($this -> upload_directory, 0755) == false) {
                    throw new Exception($this -> upload_directory." 디렉토리를 생성하지 못하였습니다. 퍼미션을 확인하시기 바랍니다.");
                }
            }
        }
        private function _mkUploadSubDir() {
            if (is_writable($this -> upload_directory) == false) {
                throw new Exception($this -> upload_directory." 에 쓰기 권한이 없습니다. ".$this -> upload_subdirectory."디렉토리를 생성하지 못하였습니다.");
            } else {
                $uploaded_path = $this -> upload_directory."/".$this -> upload_subdirectory;
                if (is_dir($uploaded_path) == false) {
                    if (@mkdir($uploaded_path, 0755) == false) {
                        throw new Exception($uploaded_path." 디렉토리를 생성하지 못하였습니다. 퍼미션을 확인하시기 바랍니다.");
                    }
                } else {
                    if (is_writable($uploaded_path) == false) {
                        throw new Exception($uploaded_path." 디렉토리에 쓰기 권한이 없습니다. 파일을 업로드 할 수 없습니다.");
                    }
                }
            }
        }
        public function uploadedFiles() {
            $saveNameArray = array();
            if (is_array($this -> upload_tmpFileName)) {
                $this -> _mkUploadDir();
                $this -> _mkUploadSubDir();
                for ($i = 0; $i < sizeof($this -> upload_tmpFileName); $i ++) {
                    if($this -> upload_fileName[$i] != ""){
                        // $uploaded_filename = $this -> upload_directory."/".$this -> upload_subdirectory."/".$this -> upload_fileName[$i];
                        $savefileRan =  time() . $this-> generateRandomString(10);
                        $uploaded_filename = $this -> upload_directory."/".$this -> upload_subdirectory."/".$savefileRan.".".$this->upload_fileExt[$i];
                        // var_dump($this -> upload_tmpFileName[$i]);
                        if (@move_uploaded_file($this -> upload_tmpFileName[$i], $uploaded_filename) == false) {
                            throw new Exception($uploaded_filename." 을 저장하지 못하였습니다.");
                        } else {
                            $saveNameArray[$i]['tmpfileNm'] = $this -> upload_tmpFileName[$i];
                            $saveNameArray[$i]['orifileNm'] = $this -> upload_fileName[$i];
                            $saveNameArray[$i]['saveFileNm'] = $savefileRan.".".$this->upload_fileExt[$i];
                            $this -> upload_count += 1;
                        }
                        @unlink($this -> upload_tmpFileName[$i]);
                    }
                }

            } else {
                if (is_uploaded_file($this -> upload_tmpFileName)) {
                    $this -> _mkUploadDir();
                    $this -> _mkUploadSubDir();
                    $savefileRan = time() . $this->generateRandomString(10);
                    // $uploaded_filename = $this ->upload_directory."/".$this -> upload_subdirectory."/".$this -> upload_fileName;
                    $uploaded_filename = $this -> upload_directory."/".$this -> upload_subdirectory."/".$savefileRan.".".$this->upload_fileExt[$i];
                    if (@move_uploaded_file($this -> upload_tmpFileName, $uploaded_filename) == false) {
                        throw new Exception($uploaded_filename." 을 저장하지 못하였습니다.");
                    } else {
                        $this -> upload_count += 1;
                    }
                    @unlink($this -> upload_tmpFileName);
                    $saveNameArray[0]['tmpfileNm'] = $this -> upload_tmpFileName;
                    $saveNameArray[0]['orifileNm'] = $this -> upload_fileName;
                    $saveNameArray[0]['saveFileNm'] = $savefileRan.".".$this->upload_fileExt[$i];
                }
            }
            return $saveNameArray;
        }
        // 이미지정보
        private function _getImageSize($tmp_file) {
            $img = @getimagesize($tmp_file);
            $img[0] = $img[0]
                ? $img[0]
                : 0;
            $img[1] = $img[1]
                ? $img[1]
                : 0;
            return $img;
        }
        // 금지 확장자명을 허용 확장자로 변경하여 파일명 지정
        private function _checkFileName($fileName) {
            $fileName = strtolower($fileName);
            foreach($this -> denied_ext as $key => $value) {
                if ($this -> _getExtension($fileName) == trim($key)) {
                    $expFileName = explode(".", $fileName);
                    for ($i = 0; $i < sizeof($expFileName) - 1; $i ++) {
                        $fname .= $expFileName[$i].$value;
                    }
                    return $fname;
                }
            }
            return $fileName;
        }
        /**
    * 파일명의 빈 부분을 "_" 로 변경
    */
        private function _emptyToUnderline($fileName) {
            return preg_replace("/\ /i", "_", $fileName);
        }
        /**
    * 확장자 추출
    */
        private function _getExtension($fileName) { // return strtolower(substr(strrchr($fileName, "."), 1));
            $path = pathinfo($fileName);
            return $path['extension'];
        }
        /**
    * 이미지 파일이 아닌 파일이 존재한다면 에러
    */
        public function checkImageOnly() {
            if (is_array($this -> upload_tmpFileName)) {
                for ($i = 0; $i < sizeof($this -> upload_tmpFileName); $i ++) {
                    if ($this -> _isThisImageFile($this -> upload_fileType[$i]) == false) {
                        throw new Exception($this -> upload_fileName[$i]." 은 이미지 파일이 아닙니다.");
                    }
                }
            } else {
                if (is_uploaded_file($this -> upload_tmpFileName)) {
                    if ($this -> _isThisImageFile($this -> upload_fileType) == false) {
                        throw new Exception($this -> upload_fileName." 은 이미지 파일이 아닙니다.");
                    }
                }
            }
        }
        /**
    * gd library information - array
    * --------------------------------------------------------------------------------
    * GD Version : string value describing the installed libgd version.
    * Freetype Support : boolean value. TRUE if Freetype Support is installed.
    * Freetype Linkage : string value describing the way in which Freetype was linked. Expected values are: 'with freetype',
    * 'with TTF library', and 'with unknown library'. This element will only be defined if Freetype Support
    * evaluated to TRUE.
    * T1Lib Support : boolean value. TRUE if T1Lib support is included.
    * GIF Read Support : boolean value. TRUE if support for reading GIF images is included.
    * GIF Create Support : boolean value. TRUE if support for creating GIF images is included.
    * JPG Support : boolean value. TRUE if JPG support is included.
    * PNG Support : boolean value. TRUE if PNG support is included.
    * WBMP Support : boolean value. TRUE if WBMP support is included.
    * XBM Support : boolean value. TRUE if XBM support is included.
    * --------------------------------------------------------------------------------
    */
        public function makeThumbnailed($max_width, $max_height, $thumb_head = null) {
            if (extension_loaded("gd") == false) {
                throw new Exception("GD 라이브러리가 설치되어 있지 않습니다.");
            } else {
                $gd = @gd_info();
                if (substr_count(strtolower($gd['GD Version']), "2.") == 0) {
                    $this -> _thumbnailedOldGD($max_width, $max_height, $thumb_head);
                } else {
                    $this -> _thumbnailedNewGD($max_width, $max_height, $thumb_head);
                }
            }
        }
        /**
    * GD Library 1.X 버전대를 위한 섬네일 함수
    *
    * GIF 포맷을 지원하지 않음
    */
        private function _thumbnailedOldGD($max_width, $max_height, $thumb_head) {
            if (is_array($this -> upload_tmpFileName)) {
                $this -> _mkUploadDir();
                $this -> _mkUploadSubDir();
                for ($i = 0; $i < sizeof($this -> upload_tmpFileName); $i ++) {
                    if ($this -> _isThisImageFile($this -> upload_fileType[$i]) == true) {
                        switch ($this -> upload_fileImageType[$i]) {
                            case 1:
                                $im = @imagecreatefromgif($this -> upload_tmpFileName[$i]);
                                break;
                            case 2:
                                $im = @imagecreatefromjpeg($this -> upload_tmpFileName[$i]);
                                break;
                            case 3:
                                $im = @imagecreatefrompng($this -> upload_tmpFileName[$i]);
                                break;
                        }
                        if (!$im) {
                            throw new Exception("썸네일 이미지 생성 중 문제가 발생하였습니다.");
                        } else {
                            $sizemin = $this -> _getWidthHeight($this -> upload_fileWidth[$i], $this -> upload_fileHeight[$i], $max_width, $max_height);
                            $small = @imagecreate($sizemin[width], $sizemin[height]);
                            @imagecolorallocate($small, 255, 255, 255);
                            @imagecopyresized($small, $im, 0, 0, 0, 0, $sizemin[width], $sizemin[height], $this -> upload_fileWidth[$i], $this -> upload_fileHeight[$i]);
                            $thumb_head = ($thumb_head != null)
                                ? $thumb_head
                                : "thumb";
                            $thumb_filename = $this -> upload_directory."/".$this -> upload_subdirectory."/".$this -> upload_fileName[$i].".${thumb_head}";
                            if ($this -> upload_fileImageType[$i] == 2) {
                                if (@imagejpeg($small, $thumb_filename, 100) == false) {
                                    throw new Exception("jpg/jpeg 썸네일 이미지를 생성하지 못하였습니다.");
                                }
                            } else if ($this -> upload_fileImageType[$i] == 3) {
                                if (@imagepng($small, $thumb_filename) == false) {
                                    throw new Exception("png 썸네일 이미지를 생성하지 못하였습니다.");
                                }
                            }
                            if ($small != null) {
                                @imagedestroy($small);
                            }
                            if ($im != null) {
                                @imagedestroy($im);
                            }
                        }
                    }
                }
            } else {
                if (is_uploaded_file($this -> upload_tmpFileName)) {
                    $this -> _mkUploadDir();
                    $this -> _mkUploadSubDir();
                    if ($this -> _isThisImageFile($this -> upload_fileType) == true) {
                        switch ($this -> upload_fileImageType) {
                            case 1:
                                $im = @imagecreatefromgif($this -> upload_tmpFileName);
                                break;
                            case 2:
                                $im = @imagecreatefromjpeg($this -> upload_tmpFileName);
                                break;
                            case 3:
                                $im = @imagecreatefrompng($this -> upload_tmpFileName);
                                break;
                        }
                        if (!$im) {
                            throw new Exception("썸네일 이미지 생성 중 문제가 발생하였습니다.");
                        } else {
                            $sizemin = $this -> _getWidthHeight($this -> upload_fileWidth, $this -> upload_fileHeight, $max_width, $max_height);
                            $small = @imagecreate($sizemin[width], $sizemin[height]);
                            @imagecolorallocate($small, 255, 255, 255);
                            @imagecopyresized($small, $im, 0, 0, 0, 0, $sizemin[width], $sizemin[height], $this -> upload_fileWidth, $this -> upload_fileHeight);
                            $thumb_head = ($thumb_head != null)
                                ? $thumb_head
                                : "thumb";
                            $thumb_filename = $this -> upload_directory."/".$this -> upload_subdirectory."/".$this -> upload_fileName.".${thumb_head}";
                            if ($this -> upload_fileImageType == 2) {
                                if (@imagejpeg($small, $thumb_filename, 100) == false) {
                                    throw new Exception("jpg/jpeg 썸네일 이미지를 생성하지 못하였습니다.");
                                }
                            } else if ($this -> upload_fileImageType == 3) {
                                if (@imagepng($small, $thumb_filename) == false) {
                                    throw new Exception("png 썸네일 이미지를 생성하지 못하였습니다.");
                                }
                            }
                            if ($small != null) {
                                @imagedestroy($small);
                            }
                            if ($im != null) {
                                @imagedestroy($im);
                            }
                        }
                    }
                }
            }
        }
        /**
    * GD Library 2.X 버전대를 위한 섬네일 함수
    *
    * GIF 포맷을 지원함
    */
        private function _thumbnailedNewGD($max_width, $max_height, $thumb_head) {
            if (is_array($this -> upload_tmpFileName)) {
                $this -> _mkUploadDir();
                $this -> _mkUploadSubDir();
                for ($i = 0; $i < sizeof($this -> upload_tmpFileName); $i ++) {
                    if ($this -> _isThisImageFile($this -> upload_fileType[$i]) == true) {
                        switch ($this -> upload_fileImageType[$i]) {
                            case 1:
                                $im = @imagecreatefromgif($this -> upload_tmpFileName[$i]);
                                break;
                            case 2:
                                $im = @imagecreatefromjpeg($this -> upload_tmpFileName[$i]);
                                break;
                            case 3:
                                $im = @imagecreatefrompng($this -> upload_tmpFileName[$i]);
                                break;
                        }
                        $sizemin = $this -> _getWidthHeight($this -> upload_fileWidth[$i], $this -> upload_fileHeight[$i], $max_width, $max_height);
                        $small = @imagecreatetruecolor($sizemin[width], $sizemin[height]);
                        @imagecolorallocate($small, 255, 255, 255);
                        @imagecopyresampled($small, $im, 0, 0, 0, 0, $sizemin[width], $sizemin[height], $this -> upload_fileWidth[$i], $this -> upload_fileHeight[$i]);
                        $thumb_head = ($thumb_head != null)
                            ? $thumb_head
                            : "thumb";
                        $thumb_filename = $this -> upload_directory."/".$this -> upload_subdirectory."/".$this -> upload_fileName[$i].".${thumb_head}";
                        if ($this -> upload_fileImageType[$i] == 1) {
                            if (@imagegif($small, $thumb_filename) == false) {
                                throw new Exception("gif 썸네일 이미지를 생성하지 못하였습니다.");
                            }
                        } else if ($this -> upload_fileImageType[$i] == 2) {
                            if (@imagejpeg($small, $thumb_filename, 100) == false) {
                                throw new Exception("jpg/jpeg 썸네일 이미지를 생성하지 못하였습니다.");
                            }
                        } else if ($this -> upload_fileImageType[$i] == 3) {
                            if (imagepng($small, $thumb_filename) == false) {
                                throw new Exception("png 썸네일 이미지를 생성하지 못하였습니다.");
                            }
                        }
                        if ($small != null) {
                            @imagedestroy($small);
                        }
                        if ($im != null) {
                            @imagedestroy($im);
                        }
                    }
                }
            } else {
                if (is_uploaded_file($this -> upload_tmpFileName)) {
                    $this -> _mkUploadDir();
                    $this -> _mkUploadSubDir();
                    if ($this -> _isThisImageFile($this -> upload_fileType) == true) {
                        switch ($this -> upload_fileImageType) {
                            case 1:
                                $im = @imagecreatefromgif($this -> upload_tmpFileName);
                                break;
                            case 2:
                                $im = @imagecreatefromjpeg($this -> upload_tmpFileName);
                                break;
                            case 3:
                                $im = @imagecreatefrompng($this -> upload_tmpFileName);
                                break;
                        }
                        $sizemin = $this -> _getWidthHeight($this -> upload_fileWidth, $this -> upload_fileHeight, $max_width, $max_height);
                        $small = @imagecreatetruecolor($sizemin[width], $sizemin[height]);
                        @imagecolorallocate($small, 255, 255, 255);
                        @imagecopyresampled($small, $im, 0, 0, 0, 0, $sizemin[width], $sizemin[height], $this -> upload_fileWidth, $this -> upload_fileHeight);
                        $thumb_head = ($thumb_head != null)
                            ? $thumb_head
                            : "thumb";
                        $thumb_filename = $this -> upload_directory."/".$this -> upload_subdirectory."/".$this -> upload_fileName.".${thumb_head}";
                        if ($this -> upload_fileImageType == 1) {
                            if (@imagegif($small, $thumb_filename) == false) {
                                throw new Exception("gif 썸네일 이미지를 생성하지 못하였습니다.");
                            }
                        } else if ($this -> upload_fileImageType == 2) {
                            if (@imagejpeg($small, $thumb_filename, 100) == false) {
                                throw new Exception("jpg/jpeg 썸네일 이미지를 생성하지 못하였습니다.");
                            }
                        } else if ($this -> upload_fileImageType == 3) {
                            if (imagepng($small, $thumb_filename) == false) {
                                throw new Exception("png 썸네일 이미지를 생성하지 못하였습니다.");
                            }
                        }
                        if ($small != null) {
                            @imagedestroy($small);
                        }
                        if ($im != null) {
                            @imagedestroy($im);
                        }
                    }
                }
            }
        }
        /**
    * 제한된 가로/세로 길이에 맞추어 원본이미지의 줄인 windth, height 값을 리턴
    * _getWidthHeight(원본이미지가로, 원본이미지세로, 제한가로, 제한세로)
    */
        private function _getWidthHeight($org_width, $org_height, $max_width, $max_height) {
            $img = array();
            if ($org_width <= $max_width && $org_height <= $max_height) {
                $img[width] = $org_width;
                $img[height] = $org_height;
            } else {
                if ($org_width > $org_height) {
                    $img[width] = $max_width;
                    $img[height] = ceil($org_height * $max_width / $org_width);
                } else if ($org_width < $org_height) {
                    $img[width] = ceil($org_width * $max_height / $org_height);
                    $img[height] = $max_height;
                } else {
                    $img[width] = $max_width;
                    $img[height] = $max_height;
                }
                if ($img[width] > $max_width) {
                    $img[width] = $max_width;
                    $img[height] = ceil($org_height * $max_width / $org_width);
                }
                if ($img[height] > $max_height) {
                    $img[width] = ceil($org_width * $max_height / $org_height);
                    $img[height] = $max_height;
                }
            }
            return $img;
        }
        public function __destruct() {}
    }
?>
