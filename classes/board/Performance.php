<?php
    namespace classes\board;
    use classes\MyPDO as MyPDO;
    use classes\Common as Common;
    use classes\file\FileUpload as FileUpload;

    class Performance extends Common{
        private $totalCount = 0;
        private $pageSize ;
        private $startNum = 1 ;

        private $si_idx ;

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

            $this->si_idx = $_REQUEST['si_idx'];
        }


        //리스트
        function getList(){
            try{
                $db = new MyPDO();

                $bindArray = array();
                $sql = " SELECT
                             	A.si_idx
                             	,A.si_year
                             	,A.si_num
                             	,A.si_cnt
                             	,A.si_price
                             	,A.si_file_1
                             	,A.si_reg_date
                             	,A.si_up_date
                               ,B.af_idx, B.af_type, B.af_save_file_nm, B.af_ori_file_nm, B.af_file_dir, B.af_save_file_detail
                             FROM
                             	WIV2_SCHOLARSHIP_INFO A
                              	LEFT JOIN WIV2_APPLY_ATTACH_FILE B ON A.si_file_1 = B.af_idx
                             WHERE si_del_yn = 'N'
                             ORDER BY si_idx DESC  ";

                $stmt = $db->prepare($sql);
                $stmt->execute();
                $result = $stmt->fetchAll();

                $list = array();
                foreach ($result as $i => $row) {
                    $list[$i]['num'] = $i;

                    $list[$i]['si_idx'] = $row->si_idx;
                    $list[$i]['si_year'] = $row->si_year;
                    $list[$i]['si_num'] = $row->si_num;
                    $list[$i]['si_cnt'] = number_format($row->si_cnt);
                    $list[$i]['si_price'] = number_format($row->si_price);
                    // $list[$i]['si_file_1'] =  $this->getFile($row->si_file_1);
                    $list[$i]['si_reg_date'] = $row->si_reg_date;
                    $list[$i]['si_up_date'] = $row->si_up_date;
                    $list[$i]['si_del_yn'] = $row->si_del_yn;

                    $list[$i]['af_idx'] = $row->af_idx;
                    $list[$i]['af_type'] = $row->af_type;
                    $list[$i]['af_ori_file_nm'] = $row->af_ori_file_nm;
                    $list[$i]['af_save_file_nm'] = $row->af_save_file_nm;
                    $list[$i]['af_file_dir'] = $row->af_file_dir;
                    $list[$i]['af_save_file_detail'] = $row->af_save_file_detail;

                    $list[$i]['fileDown'] = $row->af_file_dir.'/'.$row->af_save_file_nm;
                    $list[$i]['fileDownNm'] = $row->af_ori_file_nm;
                    if($row->af_idx != ""){
                        $fileNm = $row->af_file_dir . '/' . $row->af_save_file_nm;
                        $list[$i]['fileDownUrl'] = $this->getViewUrl . '/fileDown.php?fileNm=' .$fileNm. '&oriFileNm='. $row->af_ori_file_nm;
                    }

                }
                return $list;

            }catch(Exception $e){
                echo $e;
                exit;
            }
        }

        function getView(){

            try{
                $db = new MyPDO();

                $bindArray = array();
                $sql = " SELECT
                            SUM(si_cnt) as sum_si_cnt
                            ,SUM(si_price) as sum_si_price
                         FROM
                            WIV2_SCHOLARSHIP_INFO
                         WHERE si_del_yn = 'N'  ";

                $stmt = $db->prepare($sql);
                $stmt->execute($bindArray);
                $result = $stmt->fetch();

                $view = array();

                $view['sum_si_cnt'] = $result->sum_si_cnt;
                $view['sum_si_price'] = $result->sum_si_price;

                return $view;

            }catch(Exception $e){
                echo $e;
                exit;
            }
        }

        function getFile($idx){
            try{
                $db = new MyPDO();

                $bindArray = array();
                $sql = "    SELECT A.af_idx, A.af_type, A.af_ori_file_nm, A.af_save_file_nm, A.af_file_dir, A.af_save_file_detail
                            FROM WIV2_APPLY_ATTACH_FILE A
                            WHERE A.af_idx = :idx
                        ";
                $bindArray[':idx'] = $idx;

                $stmt = $db->prepare($sql);
                $stmt->execute($bindArray);
                $result = $stmt->fetch();

                $view['af_idx'] = $result->af_idx;
                $view['af_type'] = $result->af_type;
                $view['af_ori_file_nm'] = $result->af_ori_file_nm;
                $view['af_save_file_nm'] = $result->af_save_file_nm;
                $view['af_file_dir'] = $result->af_file_dir;
                $view['af_save_file_detail'] = $result->af_save_file_detail;

                $view['fileDown'] = $result->af_file_dir.'/'.$result->af_save_file_nm;
                $view['fileDownNm'] = $result->af_ori_file_nm;
                if($result->af_idx != ""){
                    $fileNm = $result->af_file_dir . '/' . $result->af_save_file_nm;
                    $view['fileDownUrl'] = $this->getViewUrl . '/fileDown.php?fileNm=' .$fileNm. '&oriFileNm='. $result->af_ori_file_nm;
                }

                return $view;

            }catch(Exception $e){
                exit($e->getMessage());
            }

        }

    }
?>
