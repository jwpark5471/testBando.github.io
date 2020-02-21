<?php
    namespace classes\Board;
    use classes\MyPDO as MyPDO;
    use classes\Common as Common;
    class Notice extends Common{
        private $bType = "";
        private $totalCount = 0;
        private $pageSize ;
        private $startNum = 1 ;

        public $pageNum ;
        public $searchTxt ;
        public $searchType1 ;
        public $searchType2 ;


        function __construct($bType){
            $this->bType = $bType;
            $this->pageSize = $_REQUEST['pageSize'] != "" ? $_REQUEST['pageSize'] : 10;
            $this->pageNum = $_REQUEST['pageNum'] != "" ? $_REQUEST['pageNum'] : 1;

            $this->searchTxt = $_REQUEST['searchTxt'];
            $this->searchType1 = $_REQUEST['searchType1'];
            $this->searchType2 = $_REQUEST['searchType2'];
        }

        // 전체 카운트
        function getTotalCount(){
            $bindArray = array();
            try{
                // $db = new MyPDO("mysql:host=$this->host;dbname=$this->database;charset=utf8", $this->user, $this->password);
                $db = new MyPDO();
                $sql = " SELECT count(*) as cnt FROM WIV2_BOARD A LEFT JOIN WIV2_BOARD_CONF B ON A.b_kind = B.bc_idx  WHERE A.b_type = :bType ";
                $bindArray[':bType'] = $this->bType;

                if($this->searchType1 != ""){
                	$sql .= "     AND B.bc_idx = :searchType1 ";
                    $bindArray[':searchType1'] = $this->searchType1;
                }
                if($this->searchTxt != ""){
                	if($this->searchType2 !=""){
                		$sql .= " AND :searchType2 LIKE :searchTxt ";
                        // $sql .= " AND :searchType2 LIKE concat('%', :searchTxt ,'%') ";
                		// $sql .= " AND :searchType2 = :searchTxt ";
                        $bindArray[':searchType2'] = $this->searchType2;
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
        function getList(){
            $bindArray = array();
            try{
                $db = new MyPDO();
                $sql =  "   SELECT  A.b_idx, A.b_type, A.b_kind, A.b_hit, A.b_si_disp, A.b_thumb_img, A.b_subject, A.b_contents, A.b_reg_date, A.b_up_date, A.b_del_yn
                                    ,B.bc_kind_name, B.bc_kind_view_yn, B.bc_kind_del_yn
         		                    ,( SELECT count(*) as cnt FROM WIV2_BOARD_ATTACH_FILE C WHERE C.b_idx = A.b_idx ) as file_cnt
                            FROM 	WIV2_BOARD A LEFT JOIN
     		                        WIV2_BOARD_CONF B ON A.b_kind = B.bc_idx
                            WHERE A.b_type = :bType
                        ";
                $bindArray[':bType'] = $this->bType;

                if($this->searchType1 != ""){
                	$sql .= "     AND B.bc_idx = :searchType1 ";
                    $bindArray[':searchType1'] = $this->searchType1;
                }
                if($this->searchTxt != ""){
                	if($this->searchType2 !=""){
                		$sql .= " AND :searchType2 LIKE :searchTxt ";
                        // $sql .= " AND :searchType2 LIKE concat('%', :searchTxt ,'%') ";
                		// $sql .= " AND :searchType2 = :searchTxt ";
                        $bindArray[':searchType2'] = $this->searchType2;
                        $bindArray[':searchTxt'] = '%'.$this->searchTxt.'%';
                	}else{
                		$sql .= " AND ( A.b_subject LIKE :searchTxt1 OR A.b_contents LIKE :searchTxt2 ) ";
                        $bindArray[':searchTxt1'] = '%'.$this->searchTxt.'%';
                        $bindArray[':searchTxt2'] = '%'.$this->searchTxt.'%';
                	}
                }

                $sql .= " ORDER BY A.b_idx DESC LIMIT :pageNum,:pageSize";

                $bindArray[':pageNum'] = $this->startNum;
                $bindArray[':pageSize'] = $this->pageSize;

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
                    $list[$i]['bc_kind_name'] = $row->bc_kind_name;
                    $list[$i]['bc_kind_view_yn'] = $row->bc_kind_view_yn;
                    $list[$i]['bc_kind_view'] =  $row->bc_kind_view_yn =="N"?'(미사용)':'' ;
                    $list[$i]['file_cnt'] = $row->file_cnt;
                    $num--;
                }
                return $list;

            }catch(Exception $e){
                echo $e;
                exit;
            }
        }

        function getKindList(){
            $bindArray = array();
            try{
                $db = new MyPDO();

                $sql = " SELECT bc_idx, bc_type, bc_kind_order, bc_kind_name, bc_kind_view_yn ";
                $sql .= " FROM WIV2_BOARD_CONF ";
                $sql .= " WHERE bc_type = :bType AND bc_kind_view_yn = 'Y' ";
                $bindArray[':bType'] = $this->bType;

                $stmt = $db->prepare($sql);
                $stmt->execute($bindArray);
                $result = $stmt->fetchAll();

                $list = array();
                foreach ($result as $i => $row) {
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

        function paging($page = null,$pageScale = 10){
            $page = $page == null ? $this->pageNum : $page;
            $count = $this->totalCount;
            $totalPage = ceil($count/$this->pageSize);

            $param = "&pageSize=".$this->pageSize;
            $param .= "&searchTxt=".$this->searchTxt;
            $param .= "&searchType1=".$this->searchType1;
            $param .= "&searchType2=".$this->searchType2;

            $pagingStr = "";

            if($page > 1){
                $pagingStr .= '<li class="paginate_button page-item previous" ><a class="page-link" href="'.$_SERVER[PHP_SELF].'?pageNum=1'.$param.'"> ≪ </a></li>';
            }else{
                $pagingStr .= '<li class="paginate_button page-item previous" ><a class="page-link" href="#none" style="background-color:#e4e4e4;color:#6c757d;"> ≪ </a></li>';
            }

            $startPage = ( (ceil( $page / $pageScale ) - 1) * $pageScale ) + 1;
            $endPage = $startPage + $pageScale - 1;

            if ($startPage > 1){
                $pagingStr .= '<li class="paginate_button page-item previous" id="dataTable_previous" ><a class="page-link" href="'.$_SERVER[PHP_SELF].'?pageNum='.($startPage - 1).$param.'"> ＜ </a></li>';
            }else{
                $pagingStr .= '<li class="paginate_button page-item previous" id="dataTable_previous" ><a class="page-link" href="#none" style="background-color:#e4e4e4;color:#6c757d;"> ＜ </a></li>';
            }

            if ($totalPage >= 1) {
                for ($i=$startPage;$i<=$endPage;$i++) {
                    if($i > $totalPage){ break; }
                    if ($page != $i){
                        $pagingStr .= '<li class="paginate_button page-item" ><a class="page-link" href="'.$_SERVER[PHP_SELF].'?pageNum='.$i.$param.'">'.$i.' </a></li>';
                    }else{
                        $pagingStr .= '<li class="paginate_button page-item active" ><a class="page-link" href="#none">'.$i.' </a></li>';
                    }
                }
            }

            if ($totalPage > $endPage){
                $pagingStr .= '<li class="paginate_button page-item next" id="dataTable_next" ><a class="page-link" href="'.$_SERVER[PHP_SELF].'?pageNum='.($endPage + 1).$param.'"> ＞ </a></li>';
            }else{
                $pagingStr .= '<li class="paginate_button page-item next" id="dataTable_next" ><a class="page-link" href="#none" style="background-color:#e4e4e4;color:#6c757d;"> ＞ </a></li>';
            }


            if ($page < $totalPage) {
                $pagingStr .= '<li class="paginate_button page-item next" id="dataTable_next" ><a class="page-link" href="'.$_SERVER[PHP_SELF].'?pageNum='.$totalPage.$param.'"> ≫ </a></li>';
            }else{
                $pagingStr .= '<li class="paginate_button page-item next" id="dataTable_next" ><a class="page-link" href="#none" style="background-color:#e4e4e4;color:#6c757d;"> ≫ </a></li>';
            }

            echo $pagingStr;
        }



    }
?>
