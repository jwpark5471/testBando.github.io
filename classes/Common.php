<?php
    namespace classes;

    class Common{
        protected $applyUploadDir = '';
        protected $applyinfoUploadDir = '';
        protected $boardUploadDir = '';
        protected $qnaUploadDir = '';
        protected $performanceUploadDir = '';
        protected $getViewUrl = '';
        protected $getViewPath = '';

        function __construct(){
            $this->applyUploadDir = $_SERVER[DOCUMENT_ROOT].'/v2/@upload/apply';
            $this->applyinfoUploadDir = $_SERVER[DOCUMENT_ROOT].'/v2/@upload/applyinfo';
            $this->boardUploadDir = $_SERVER[DOCUMENT_ROOT].'/v2/@upload/board';
            $this->qnaUploadDir = $_SERVER[DOCUMENT_ROOT].'/v2/@upload/qna';
            $this->performanceUploadDir = $_SERVER[DOCUMENT_ROOT].'/v2/@upload/performance';

            $this->getViewUrl = '/v2/view';
            $this->getViewPath = $_SERVER[DOCUMENT_ROOT].'/v2/view';
        }

        function setSelected($field = null,$value = null){
            if($field == $value || ($field == null && $value == null ) ){
                echo "selected='selected'";
            }
        }
        function setChecked($field = null,$value = null){
            if($field == $value || ($field == null && $value == null ) ){
                echo "checked='checked'";
            }
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

    }

?>
