<?php
    namespace classes\board;
    use classes\MyPDO as MyPDO;
    use classes\Common as Common;
    use classes\file\FileUpload as FileUpload;

    class Kind extends Common{
        public $bType ;

        function __construct(){
            parent::__construct();
            $this->bType  = $_REQUEST['bType'] != "" ? $_REQUEST['bType'] : 'NEWS' ;
        }

        //리스트
        function getList(){
            try{
                $db = new MyPDO();

                $bindArray = array();
                $sql =  "   SELECT  bc_idx, bc_type, bc_kind_order, bc_kind_name, bc_kind_view_yn, bc_kind_del_yn
                            FROM 	WIV2_BOARD_CONF
                             WHERE bc_type = :bType
                        ";

                $bindArray[':bType'] = $this->bType;

                $stmt = $db->prepare($sql);
                $stmt->execute($bindArray);
                $result = $stmt->fetchAll();

                $list = array();
                $num = 1;
                foreach ($result as $i => $row) {
                    $list[$i]['num'] = $num;

                    $list[$i]['bc_idx'] = $row->bc_idx;
                    $list[$i]['bc_type'] = $row->bc_type;
                    $list[$i]['bc_kind_order'] = $row->bc_kind_order;
                    $list[$i]['bc_kind_name'] = $row->bc_kind_name;
                    $list[$i]['bc_kind_view_yn'] = $row->bc_kind_view_yn;
                    $list[$i]['bc_kind_del_yn'] = $row->bc_kind_del_yn;

                    $num++;
                }
                return $list;

            }catch(Exception $e){
                echo $e;
                exit;
            }
        }

        function update(){
            try{
                $db = new MyPDO();

                $kindArray = $_POST['kindArray'];

                try{
                    $db->beginTransaction();

                    foreach($kindArray as $row ){
                        $state = $row['bc_kind_state'];
                        $bindArray = array();
                        if($state == 'Update'){
                            $sql =  "   UPDATE WIV2_BOARD_CONF SET
                                            bc_kind_order = :bc_kind_order, bc_kind_name = :bc_kind_name, bc_kind_view_yn = :bc_kind_view_yn
                                        WHERE bc_idx = :bc_idx
                                    ";
                            $bindArray[':bc_kind_order'] = $row['bc_kind_order'];
                            $bindArray[':bc_kind_name'] = $row['bc_kind_name'];
                            $bindArray[':bc_kind_view_yn'] = $row['bc_kind_view_yn'];
                            $bindArray[':bc_idx'] = $row['bc_idx'];

                        }else if($state == 'Insert'){
                            $sql =  "   INSERT INTO WIV2_BOARD_CONF SET
                                            bc_type = :bType, bc_kind_order = :bc_kind_order, bc_kind_name = :bc_kind_name, bc_kind_view_yn = :bc_kind_view_yn
                                    ";
                            $bindArray[':bType'] = $row['bType'];
                            $bindArray[':bc_kind_order'] = $row['bc_kind_order'];
                            $bindArray[':bc_kind_name'] = $row['bc_kind_name'];
                            $bindArray[':bc_kind_view_yn'] = $row['bc_kind_view_yn'];
                        }
                        $stmt = $db->prepare($sql);
                        $stmt->execute($bindArray);
                    }

                    $bindArray = array();
                    $sql = " SELECT COUNT(*) AS cnt FROM WIV2_BOARD_CONF WHERE bc_type = :bType AND  bc_kind_view_yn = 'Y' ";
                    $bindArray[':bType'] = $this->bType;
                    $stmt = $db->prepare($sql);
                    $stmt->execute($bindArray);
                    $result = $stmt->fetch();
                    $kindCnt = $result->cnt;

                    if($kindCnt < 1){
                            $bindArray = array();
                            $sql = " UPDATE WIV2_BOARD_CONF SET
                                        bc_kind_view_yn = 'Y'
                                    WHERE bc_type = '$boardType' AND  bc_kind_view_yn = 'N'  ORDER BY bc_kind_order ASC LIMIT 1 ";
                            $bindArray[':bType'] = $this->bType;
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

        function delete(){
            try{
                $db = new MyPDO();
                $FileUpload = new FileUpload($this->boardUploadDir);

                $bType = $_POST['bType'];
                $bc_idx = $_POST['bc_idx'];
                $bc_kind_order = $_POST['bc_kind_order'];

                $bindArray = array();
                $sql = "  SELECT COUNT(*) AS cnt FROM WIV2_BOARD WHERE b_kind = :bc_idx ";
                $bindArray[':bc_idx'] = $bc_idx;
                $stmt = $db->prepare($sql);
                $stmt->execute($bindArray);
                $result = $stmt->fetch();
                $boardCnt = $result->cnt;

                if($boardCnt > 0){
                    return 2;   // 말머리를 포함하고 있는 게시글이 있어 삭제할 수 없습니다.
                }

                $bindArray = array();
                $sql = "  SELECT COUNT(*) AS cnt FROM WIV2_BOARD_CONF WHERE bc_type = :bType ";
                $bindArray[':bType'] = $bType;
                $stmt = $db->prepare($sql);
                $stmt->execute($bindArray);
                $result = $stmt->fetch();
                $boardCnt = $result->cnt;

                if($boardCnt <= 1){
                    return 3;   // 마지막 말머리는 삭제할 수 없습니다.
                }

                try{
                    $db->beginTransaction();

                    $bindArray = array();
                    $sql = "    DELETE FROM WIV2_BOARD_CONF WHERE bc_idx = :bc_idx ";
                    $bindArray[':bc_idx'] = $bc_idx;
                    $stmt = $db->prepare($sql);
                    $stmt->execute($bindArray);

                    $bindArray = array();
                    $sql = "     UPDATE WIV2_BOARD_CONF SET bc_kind_order = bc_kind_order - 1 WHERE  bc_type = :bType AND bc_kind_order > :bc_kind_order ";
                    $bindArray[':bType'] = $bType;
                    $bindArray[':bc_kind_order'] = $bc_kind_order;
                    $stmt = $db->prepare($sql);
                    $stmt->execute($bindArray);

                    $db->commit();

                    return 1;

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
