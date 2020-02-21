<?php
    namespace classes\board;
    use classes\MyPDO as MyPDO;
    use classes\Common as Common;
    use classes\file\FileUpload as FileUpload;
    class Board extends Common{
        private $bType = "";
        private $totalCount = 0;
        private $pageSize ;
        private $startNum = 0 ;

        private $b_idx ;

        public $pageNum ;
        public $searchTxt ;
        public $searchType1 ;
        public $searchType2 ;


        function __construct($bType = "Notice"){
            parent::__construct();

            $this->bType = $bType;
            $this->pageSize = $_REQUEST['pageSize'] != "" ? $_REQUEST['pageSize'] : 10;
            $this->pageNum = $_REQUEST['pageNum'] != "" ? $_REQUEST['pageNum'] : 1;

            $this->searchTxt = $_REQUEST['searchTxt'];
            $this->searchType1 = $_REQUEST['searchType1'];
            $this->searchType2 = $_REQUEST['searchType2'];

            $this->b_idx = $_REQUEST['b_idx'];
        }

        function getB_idx(){
            return $this->b_idx;
        }

        // 전체 카운트
        function getTotalCount(){
            try{
                // $db = new MyPDO("mysql:host=$this->host;dbname=$this->database;charset=utf8", $this->user, $this->password);
                $db = new MyPDO();

                $bindArray = array();
                $sql = " SELECT count(*) as cnt FROM WIV2_BOARD A LEFT JOIN WIV2_BOARD_CONF B ON A.b_kind = B.bc_idx  WHERE A.b_type = :bType ";
                $bindArray[':bType'] = $this->bType;

                if($this->searchType1 != ""){
                	$sql .= "     AND B.bc_idx = :searchType1 ";
                    $bindArray[':searchType1'] = $this->searchType1;
                }
                if($this->searchTxt != ""){
                	if($this->searchType2 !=""){
                		$sql .= " AND $this->searchType2 LIKE :searchTxt ";
                        // $sql .= " AND :searchType2 LIKE concat('%', :searchTxt ,'%') ";
                		// $sql .= " AND :searchType2 = :searchTxt ";
                        // $bindArray[':searchType2'] = $this->searchType2;
                        $bindArray[':searchTxt'] = '%'.$this->searchTxt.'%';
                	}else{
                		$sql .= " AND ( A.b_subject LIKE :searchTxt1 OR A.b_contents LIKE :searchTxt2 ) ";
                        $bindArray[':searchTxt1'] = '%'.$this->searchTxt.'%';
                        $bindArray[':searchTxt2'] = '%'.$this->searchTxt.'%';
                	}
                }
                $stmt = $db->prepare($sql);
                // $stmt->bindParam(':bType',$this->bType);
                $stmt->execute($bindArray);

                $result = $stmt->fetch();

                $this->totalCount = $result->cnt;

                $lastPage = ceil($this->totalCount/$this->pageSize);
                if($this->pageNum > $lastPage){
                    $this->pageNum = $lastPage;
                }else if($this->pageNum <= 0 ){
                    $this->pageNum = 1;
                }
                $this->startNum = ($this->pageNum-1)*$this->pageSize;

                return $result->cnt;

            }catch(Exception $e){
                echo $e;
                exit;
            }
        }

        //리스트
        function getList($bType = "", $pageSize = ""){
            if($bType == ""){
                $bType = $this->bType;
            }

            if($pageSize == ""){
                $pageSize = $this->pageSize;
            }
            try{
                $db = new MyPDO();

                $bindArray = array();
                $sql =  "   SELECT  A.b_idx, A.b_type, A.b_kind, A.b_hit, A.b_si_disp, A.b_thumb_img, A.b_subject, A.b_contents, A.b_reg_date, A.b_up_date, A.b_del_yn, A.b_new_yn
                                    ,B.bc_kind_name, B.bc_kind_view_yn, B.bc_kind_del_yn
         		                    ,( SELECT count(*) as cnt FROM WIV2_BOARD_ATTACH_FILE C WHERE C.b_idx = A.b_idx ) as file_cnt
                                    ,C.bf_idx, C.bf_type, C.bf_ori_file_nm, C.bf_save_file_nm, C.bf_file_dir, C.bf_save_file_detail
                            FROM 	WIV2_BOARD A LEFT JOIN
     		                        WIV2_BOARD_CONF B ON A.b_kind = B.bc_idx
                                    LEFT JOIN WIV2_BOARD_ATTACH_FILE C ON A.b_idx = C.b_idx AND C.bf_type = 0
                            WHERE A.b_type = :bType
                        ";
                $bindArray[':bType'] = $bType;

                if($this->searchType1 != ""){
                	$sql .= "     AND B.bc_idx = :searchType1 ";
                    $bindArray[':searchType1'] = $this->searchType1;
                }
                if($this->searchTxt != ""){
                	if($this->searchType2 !=""){
                		$sql .= " AND $this->searchType2 LIKE :searchTxt ";
                        // $sql .= " AND :searchType2 LIKE concat('%', :searchTxt ,'%') ";
                		// $sql .= " AND :searchType2 = :searchTxt ";
                        // $bindArray[':searchType2'] = $this->searchType2;
                        $bindArray[':searchTxt'] = '%'.$this->searchTxt.'%';
                	}else{
                		$sql .= " AND ( A.b_subject LIKE :searchTxt1 OR A.b_contents LIKE :searchTxt2 ) ";
                        $bindArray[':searchTxt1'] = '%'.$this->searchTxt.'%';
                        $bindArray[':searchTxt2'] = '%'.$this->searchTxt.'%';
                	}
                }

                $sql .= " ORDER BY A.b_idx DESC LIMIT :pageNum,:pageSize";

                $bindArray[':pageNum'] = $this->startNum;
                $bindArray[':pageSize'] = $pageSize;

                $stmt = $db->prepare($sql);
                $stmt->execute($bindArray);
                $result = $stmt->fetchAll();

                $list = array();
                $num = $this->totalCount-$this->startNum;
                foreach ($result as $i => $row) {
                    $list[$i]['num'] = $num;
                    $list[$i]['b_idx'] = $row->b_idx;
                    $list[$i]['b_type'] = $row->b_type;
                    $list[$i]['b_kind'] = $row->b_kind;
                    $list[$i]['b_hit'] = $row->b_hit;
                    $list[$i]['b_si_disp'] = $row->b_si_disp;
                    $list[$i]['b_thumb_img'] = $row->b_thumb_img;
                    $list[$i]['b_subject'] = $row->b_subject;
                    $list[$i]['b_contents'] = $row->b_contents;
                    $list[$i]['b_reg_date'] = $row->b_reg_date;
                    $list[$i]['b_up_date'] = $row->b_up_date;
                    $list[$i]['b_del_yn'] = $row->b_del_yn;
                    $list[$i]['b_new_yn'] = $row->b_new_yn;
                    $list[$i]['bc_kind_name'] = $row->bc_kind_name;
                    $list[$i]['bc_kind_view_yn'] = $row->bc_kind_view_yn;
                    $list[$i]['bc_kind_view'] =  $row->bc_kind_view_yn =="N"?'(미사용)':'' ;
                    $list[$i]['file_cnt'] = $row->file_cnt;

                    // $list[$i]['bf_idx'] = $row->bf_idx;
                    // $list[$i]['bf_type'] = $row->bf_type;
                    // $list[$i]['bf_ori_file_nm'] = $row->bf_ori_file_nm;
                    $list[$i]['bf_save_file_nm'] = $row->bf_save_file_nm;
                    $list[$i]['bf_file_dir'] = $row->bf_file_dir;
                    // $list[$i]['bf_save_file_detail'] = $row->bf_save_file_detail;

                    if($row->bf_idx != ''){
                        $list[$i]['thum_img'] = '/v2/@upload'. $row->bf_file_dir . '/' . $row->bf_save_file_nm;
                    }else{
                        $list[$i]['thum_img'] = $this->getViewUrl . '/resources/images/common/no_img.jpg';
                    }

                    $num--;
                }
                return $list;

            }catch(Exception $e){
                echo $e;
                exit;
            }
        }

        function getKindName($bc_idx){
            try{
                $db = new MyPDO();

                $bindArray = array();
                $sql = " SELECT bc_idx, bc_type, bc_kind_order, bc_kind_name, bc_kind_view_yn ";
                $sql .= " FROM WIV2_BOARD_CONF ";
                $sql .= " WHERE bc_idx = :bc_idx ";
                $bindArray[':bc_idx'] = $bc_idx;

                $stmt = $db->prepare($sql);
                $stmt->execute($bindArray);
                $view = $stmt->fetch();

                return $view->bc_kind_name;


            }catch(Exception $e){
                echo $e;
                exit;
            }
        }

        function getKindList(){
            try{
                $db = new MyPDO();

                $bindArray = array();
                $sql = " SELECT bc_idx, bc_type, bc_kind_order, bc_kind_name, bc_kind_view_yn ";
                $sql .= " FROM WIV2_BOARD_CONF ";
                $sql .= " WHERE bc_type = :bType AND bc_kind_view_yn = 'Y' ";
                $bindArray[':bType'] = $this->bType;

                $stmt = $db->prepare($sql);
                $stmt->execute($bindArray);
                $result = $stmt->fetchAll();

                $list = array();
                foreach ($result as $i => $row){
                    $list[$i]['bc_idx'] = $row->bc_idx;
                    $list[$i]['bc_type'] = $row->bc_type;
                    $list[$i]['bc_kind_order'] = $row->bc_kind_order;
                    $list[$i]['bc_kind_name'] = $row->bc_kind_name;
                    $list[$i]['bc_kind_view_yn'] = $row->bc_kind_view_yn;
                }

                return $list;


            }catch(Exception $e){
                echo $e;
                exit;
            }
        }

        function paging($getViewUrl,$page = null,$pageScale = 10){
            $page = $page == null ? $this->pageNum : $page;
            $count = $this->totalCount;
            $totalPage = ceil($count/$this->pageSize);

            $param = "&pageSize=".$this->pageSize;
            $param .= "&searchTxt=".$this->searchTxt;
            $param .= "&searchType1=".$this->searchType1;
            $param .= "&searchType2=".$this->searchType2;

            if($totalPage != 0){

                $pagingStr = "";

                if($page > 1){
                    // $pagingStr .= '<li class="paginate_button page-item previous" ><a class="page-link" href="'.$_SERVER[PHP_SELF].'?pageNum=1'.$param.'"> ≪ </a></li>';
                }else{
                    // $pagingStr .= '<li class="paginate_button page-item previous" ><a class="page-link" href="#none" style="background-color:#e4e4e4;color:#6c757d;"> ≪ </a></li>';
                }

                $startPage = ( (ceil( $page / $pageScale ) - 1) * $pageScale ) + 1;
                $endPage = $startPage + $pageScale - 1;

                if ($startPage > 1){
                    // $pagingStr .= '<li class="paginate_button page-item previous" id="dataTable_previous" ><a class="page-link" href="'.$_SERVER[PHP_SELF].'?pageNum='.($startPage - 1).$param.'"> ＜ </a></li>';
                    $pagingStr .= '<a href="'.$_SERVER[PHP_SELF].'?pageNum='.($startPage - 1).$param.'" class="btn prv">이전</a>';
                }else{
                    // $pagingStr .= '<li class="paginate_button page-item previous" id="dataTable_previous" ><a class="page-link" href="#none" style="background-color:#e4e4e4;color:#6c757d;"> ＜ </a></li>';
                    $pagingStr .= '<a href="#" class="btn prv">이전</a>';
                }

                if ($totalPage >= 1) {
                    for ($i=$startPage;$i<=$endPage;$i++) {
                        if($i > $totalPage){ break; }
                        if ($page != $i){
                            // $pagingStr .= '<li class="paginate_button page-item" ><a class="page-link" href="'.$_SERVER[PHP_SELF].'?pageNum='.$i.$param.'">'.$i.' </a></li>';
                            $pagingStr .= '<a href="'.$_SERVER[PHP_SELF].'?pageNum='.$i.$param.'">'.$i.'</a>';
                        }else{
                            // $pagingStr .= '<li class="paginate_button page-item active" ><a class="page-link" href="#none">'.$i.' </a></li>';
                            $pagingStr .= '<strong>'.$i.'</strong>';
                        }
                    }
                }

                if ($totalPage > $endPage){
                    // $pagingStr .= '<li class="paginate_button page-item next" id="dataTable_next" ><a class="page-link" href="'.$_SERVER[PHP_SELF].'?pageNum='.($endPage + 1).$param.'"> ＞ </a></li>';
                    $pagingStr .= '<a href="'.$_SERVER[PHP_SELF].'?pageNum='.($endPage + 1).$param.'" class="btn nxt">다음</a>';
                }else{
                    // $pagingStr .= '<li class="paginate_button page-item next" id="dataTable_next" ><a class="page-link" href="#none" style="background-color:#e4e4e4;color:#6c757d;"> ＞ </a></li>';
                    $pagingStr .= '<a href="#" class="btn nxt">다음</a>';
                }


                if ($page < $totalPage) {
                    // $pagingStr .= '<li class="paginate_button page-item next" id="dataTable_next" ><a class="page-link" href="'.$_SERVER[PHP_SELF].'?pageNum='.$totalPage.$param.'"> ≫ </a></li>';
                }else{
                    // $pagingStr .= '<li class="paginate_button page-item next" id="dataTable_next" ><a class="page-link" href="#none" style="background-color:#e4e4e4;color:#6c757d;"> ≫ </a></li>';
                }
            }
            echo $pagingStr;


        }



        function setHitCnt(){
            try{
                $db = new MyPDO();
                $bindArray = array();
                $sql .= "   UPDATE WIV2_BOARD SET b_hit = b_hit + 1 WHERE b_idx = :b_idx ";
                $bindArray[':b_idx'] = $this->b_idx;
                try{
                    $db->beginTransaction();
                    $stmt = $db->prepare($sql);
                    $stmt->execute($bindArray);
                    $db->commit();
                    setcookie('board_free_' . $this->b_idx, TRUE, time() + (60 * 60 * 24), '/');
                    return true;
                }catch(PDOExecption $e){
                    $db->rollback();
                    throw $e;
                }
            }catch(Exception $e){
                exit($e->getMessage());
            }
        }

        function getView(){
            try{
                $db = new MyPDO();

	            if(!empty($this->b_idx) && empty($_COOKIE['board_free_' . $this->b_idx])){
                    $this->setHitCnt();
                }

                $bindArray = array();
                $sql = " SELECT A.b_idx, A.b_type, A.b_new_yn, A.b_kind, A.b_hit, A.b_si_disp, A.b_thumb_img, A.b_subject
                                ,A.b_contents, A.b_reg_date, A.b_up_date, A.b_del_yn, B.bc_idx, B.bc_kind_name, B.bc_kind_order
            	 		        ,( SELECT count(*) as cnt FROM WIV2_BOARD_ATTACH_FILE C WHERE C.b_idx = A.b_idx ) as file_cnt
                                ,C.bf_idx, C.bf_type, C.bf_ori_file_nm, C.bf_save_file_nm, C.bf_file_dir, C.bf_save_file_detail
            	        FROM 	WIV2_BOARD A LEFT JOIN
            	 		        WIV2_BOARD_CONF B ON A.b_kind = B.bc_idx
                                LEFT JOIN WIV2_BOARD_ATTACH_FILE C ON A.b_idx = C.b_idx AND C.bf_type = 0
            	        WHERE A.b_idx = :b_idx ";
                $bindArray[':b_idx'] = $this->b_idx;

                $stmt = $db->prepare($sql);
                $stmt->execute($bindArray);
                $result = $stmt->fetch();

                $view = array();

                $view['b_idx'] = $result->b_idx;
    			$view['b_type'] = $result->b_type;
    			$view['b_kind'] = $result->b_kind;
    			$view['b_new_yn'] = $result->b_new_yn;
    			$view['b_hit'] = $result->b_hit;
    			$view['b_si_disp'] = $result->b_si_disp;
    			$view['b_thumb_img'] = $result->b_thumb_img;
    			$view['b_subject'] = $result->b_subject;
    			$view['b_contents'] = $result->b_contents;
    			// $view['b_contents'] = json_encode(preg_replace("/\\\\/","",$result->b_contents));
                $view['b_contents'] = str_replace('\"', '"', $result->b_contents);
    			$view['b_reg_date'] = substr($result->b_reg_date,0,10);
    			$view['b_up_date'] = $result->b_up_date;
    			$view['b_del_yn'] = $result->b_del_yn;
    			$view['bc_idx'] = $result->bc_idx;
    			$view['bc_kind_name'] = $result->bc_kind_name;
    			$view['bc_kind_order'] = $result->bc_kind_order;
    			$view['file_cnt'] = $result->file_cnt;

                $view['fileIdx'] = $result->bf_idx;

                if($result->bf_idx !=""){
                    $view['img_uri'] = '/v2/@upload'.$result->bf_file_dir.'/'.$result->bf_save_file_nm;
                }

                return $view;

            }catch(Exception $e){
                echo $e;
                exit;
            }
        }

        function getViewPNIdx($b_idx = ""){
            if($b_idx == ""){
                $b_idx = $this->b_idx;
            }
            try{
                $db = new MyPDO();

                $view = array();

                $bindArray = array();
                $sql =  "   SELECT A.b_idx, A.b_subject
                                 FROM 	WIV2_BOARD A LEFT JOIN
                                 		WIV2_BOARD_CONF B ON A.b_kind = B.bc_idx
                                 WHERE b_idx > :b_idx AND A.b_type = :bType
                        ";
                $bindArray[':b_idx'] = $b_idx;
                $bindArray[':bType'] = $this->bType;

                if($this->searchType1 != ""){
                	$sql .= "     AND B.bc_idx = :searchType1 ";
                    $bindArray[':searchType1'] = $this->searchType1;
                }
                if($this->searchTxt != ""){
                	if($this->searchType2 !=""){
                		$sql .= " AND $this->searchType2 LIKE :searchTxt ";
                        $bindArray[':searchTxt'] = '%'.$this->searchTxt.'%';
                	}else{
                		$sql .= " AND ( A.b_subject LIKE :searchTxt1 OR A.b_contents LIKE :searchTxt2 ) ";
                        $bindArray[':searchTxt1'] = '%'.$this->searchTxt.'%';
                        $bindArray[':searchTxt2'] = '%'.$this->searchTxt.'%';
                	}
                }
                $sql .= "  ORDER BY A.b_idx ASC LIMIT 1 ";

                $stmt = $db->prepare($sql);
                $stmt->execute($bindArray);
                $result = $stmt->fetch();

                $view['prevIdx'] = $result->b_idx;
    			$view['prevSubject'] = $result->b_subject;

                $bindArray = array();
                $sql =  "   SELECT A.b_idx, A.b_subject
                                 FROM 	WIV2_BOARD A LEFT JOIN
                                 		WIV2_BOARD_CONF B ON A.b_kind = B.bc_idx
                                 WHERE b_idx < :b_idx AND A.b_type = :bType
                        ";
                $bindArray[':b_idx'] = $b_idx;
                $bindArray[':bType'] = $this->bType;

                if($this->searchType1 != ""){
                	$sql .= "     AND B.bc_idx = :searchType1 ";
                    $bindArray[':searchType1'] = $this->searchType1;
                }
                if($this->searchTxt != ""){
                	if($this->searchType2 !=""){
                		$sql .= " AND $this->searchType2 LIKE :searchTxt ";
                        $bindArray[':searchTxt'] = '%'.$this->searchTxt.'%';
                	}else{
                		$sql .= " AND ( A.b_subject LIKE :searchTxt1 OR A.b_contents LIKE :searchTxt2 ) ";
                        $bindArray[':searchTxt1'] = '%'.$this->searchTxt.'%';
                        $bindArray[':searchTxt2'] = '%'.$this->searchTxt.'%';
                	}
                }
                $sql .= "    ORDER BY A.b_idx DESC LIMIT 1 ";

                $stmt = $db->prepare($sql);
                $stmt->execute($bindArray);
                $result = $stmt->fetch();

                $view['nextIdx'] = $result->b_idx;
    			$view['nextSubject'] = $result->b_subject;

                return $view;

            }catch(Exception $e){
                echo $e;
                exit;
            }
        }

        function getFileList(){
            try{
                $db = new MyPDO();

                $bindArray = array();
                $sql = "    SELECT bf_idx, bf_type, bf_ori_file_nm, bf_save_file_nm, bf_file_dir, bf_save_file_detail
                            FROM WIV2_BOARD_ATTACH_FILE A
                            WHERE A.b_idx = :b_idx AND A.bf_type = 1
                        ";
                $bindArray[':b_idx'] = $this->b_idx;

                $stmt = $db->prepare($sql);
                $stmt->execute($bindArray);
                $result = $stmt->fetchAll();

                $list = array();
                foreach ($result as $i => $row) {
                    $list[$i]['fileIdx'] = $row->bf_idx;
                    $list[$i]['saveFileNm'] = $row->bf_save_file_nm;
                    $list[$i]['oriFileNm'] = $row->bf_ori_file_nm == "" ? "파일선택" : $row->bf_ori_file_nm;
                    $list[$i]['saveFileDetail'] = $row->bf_save_file_detail;
                    $list[$i]['saveFileDir'] = $row->bf_file_dir;

                    $list[$i]['fileDown'] = $row->bf_file_dir.'/'.$row->bf_save_file_nm;
                    $list[$i]['fileDownNm'] = $list[$i]['oriFileNm'];
                    $list[$i]['fileDownUrl'] = $this->getViewUrl . '/fileDown.php?fileNm=' .$list[$i]['fileDown']. '&oriFileNm='. $list[$i]['oriFileNm'];
                }
                return $list;
            }catch(Exception $e){
                exit($e->getMessage());
            }
        }
    }
?>
