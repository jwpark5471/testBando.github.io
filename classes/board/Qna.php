<?php
    namespace classes\board;
    use classes\MyPDO as MyPDO;
    use classes\Common as Common;
    use classes\file\FileUpload as FileUpload;

    class Qna extends Common{
        private $totalCount = 0;
        private $pageSize ;
        private $startNum = 1 ;

        private $q_idx ;

        public $pageNum ;
        public $searchTxt ;
        // public $searchType1 ;
        public $searchType2 ;


        function __construct(){
            parent::__construct();

            $this->pageSize = $_REQUEST['pageSize'] != "" ? $_REQUEST['pageSize'] : 10;
            $this->pageNum = $_REQUEST['pageNum'] != "" ? $_REQUEST['pageNum'] : 1;

            $this->searchTxt = $_REQUEST['searchTxt'];
            // $this->searchType1 = $_REQUEST['searchType1'];
            $this->searchType2 = $_REQUEST['searchType2'];

            $this->q_idx = $_REQUEST['q_idx'];
        }

        function getQ_idx(){
            return $this->q_idx;
        }

        function insert(){
           try{
                $db = new MyPDO();
                $FileUpload = new FileUpload($this->qnaUploadDir);

                $q_name = $_POST[q_name];
               	$q_tel_1 = $_POST[q_tel_1];
               	$q_tel_2 = $_POST[q_tel_2];
               	$q_tel_3 = $_POST[q_tel_3];
               	$q_tel = $q_tel_1 . '-' . $q_tel_2 . '-' . $q_tel_3;
               	$q_email_1 = $_POST[q_email_1];
               	$q_email_2 = $_POST[q_email_2];
               	$q_email = $q_email_1 . '@' . $q_email_2;
               	// $q_email_sel = $_POST[q_email_sel];
               	$q_subject = $_POST[q_subject];
               	$q_contents = $_POST[q_contents];

               $bindArray = array();
               $sql = "       INSERT INTO WIV2_QNA SET
                         			q_name = :q_name
                         			,q_tel = :q_tel
                         			,q_email = :q_email
                         			,q_subject = :q_subject
                         			,q_contents = :q_contents
                         			,q_reg_date = NOW()     ";

               $bindArray[':q_name'] = $q_name;
               $bindArray[':q_tel'] = $q_tel;
               $bindArray[':q_email'] = $q_email;
               $bindArray[':q_subject'] = $q_subject;
               $bindArray[':q_contents'] = $q_contents;

               try{
                   $db->beginTransaction();

                   $stmt = $db->prepare($sql);
                   $stmt->execute($bindArray);

                   $q_idx = $db->lastInsertId();

                   $FileUpload->define($_FILES['upFile']);
                   $saveNameArray = $FileUpload->uploadedFiles();

                   foreach($saveNameArray as $i => $row){
                       $this->fileInsert($q_idx, $row, $FileUpload->upload_directory, $FileUpload->upload_subdirectory);
                   }


                   $db->commit();

                   return true;

               }catch(PDOExecption $e){
                   $db->rollback();
                   throw $e;
               }


               // var_dump($FileUpload);
           }catch(Exception $e){
               exit($e->getMessage());
           }
       }

       function fileInsert($q_idx, $saveNameArray, $upload_directory, $upload_subdirectory){
           try{
               $db = new MyPDO();

               $bindArray = array();
               $sql = "INSERT INTO WIV2_QNA_ATTACH_FILE(q_idx, qf_ori_file_nm, qf_save_file_nm, qf_file_dir, qf_save_file_detail)
                                                   VALUES(:q_idx, :oriFileNm, :saveFileNm, :upSubDir, :upDir)
                                                   ";
               $bindArray[':q_idx'] = $q_idx;
               $bindArray[':oriFileNm'] = $saveNameArray['orifileNm'];
               $bindArray[':saveFileNm'] = $saveNameArray['saveFileNm'];
               $bindArray[':upSubDir'] = "/qna/".$upload_subdirectory;
               $bindArray[':upDir'] = $upload_directory."/".$upload_subdirectory."/";

               $stmt = $db->prepare($sql);
               $stmt->execute($bindArray);

           }catch(Exception $e){
               exit($e->getMessage());
           }
       }


    }
?>
