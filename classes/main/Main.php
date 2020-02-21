<?php
    namespace classes\main;
    use classes\MyPDO as MyPDO;
    use classes\Common as Common;
    class Main extends Common{

        function __construct(){
            parent::__construct();
        }

        function getApplyRegDate(){
            try{
                $db = new MyPDO();

                $bindArray = array();
                $sql =  "    SELECT
                            	DATE_FORMAT(A.a4_reg_date,'%Y-%m-%d') AS date
                            	,COUNT(*) AS cnt
                             FROM WIV2_APPLICANT A,
				                  WIV2_APPLY_PERIOD B
                             WHERE
			                     B.ap_idx = 1
                                 AND DATE_FORMAT(A.a4_reg_date,'%Y-%m-%d') >= B.s_date
                                 AND DATE_FORMAT(A.a4_reg_date,'%Y-%m-%d') <= B.e_date
                             GROUP BY DATE
                        ";

                $stmt = $db->prepare($sql);
                $stmt->execute($bindArray);
                $result = $stmt->fetchAll();

                $list = array();
                foreach ($result as $i => $row) {
                    $list[$i]['num'] = $num;
                    $list[$i]['date'] = $row->date;
                    $list[$i]['cnt'] = $row->cnt;
                }
                return $list;

            }catch(Exception $e){
                echo $e;
                exit;
            }
        }

        function getSchoolType(){
            try{
                $db = new MyPDO();

                $bindArray = array();
                $sql =  "   SELECT
            					a2_school_type
            					 , COUNT(*) AS cnt
            				FROM
            					WIV2_APPLICANT A ,
            					WIV2_APPLY_PERIOD B
            				WHERE
            					a4_reg_date IS NOT NULL
            					AND DATE_FORMAT(A.a1_up_date,'%Y-%m-%d') >= B.s_date
            					AND  DATE_FORMAT(A.a1_up_date,'%Y-%m-%d') <= B.e_date
            				GROUP BY a2_school_type
                        ";

                $stmt = $db->prepare($sql);
                $stmt->execute($bindArray);
                $result = $stmt->fetchAll();

                $list = array();
                foreach ($result as $i => $row) {
                    //학교 타입(0:중학교, 1:고등학교, 2:중,고 입학예정, 3: 대학교, 4: 대학원(석사), 5:대학교(박사), 6: 대학교,대학원 입학예정)
                    $type = '';
                    switch($row->a2_school_type){
                        case 0 : $type = "중학교"; break;
                        case 1 : $type = "고등학교"; break;
                        case 2 : $type = "중,고 입학예정"; break;
                        case 3 : $type = "대학교"; break;
                        case 4 : $type = "대학원(석사)"; break;
                        case 5 : $type = "대학교(박사)"; break;
                        case 6 : $type = "대학교,대학원 입학예정"; break;
                    }

                    $list[$i]['num'] = $num;
                    $list[$i]['a2_school_type'] = $type;
                    $list[$i]['cnt'] = $row->cnt;
                }
                return $list;

            }catch(Exception $e){
                echo $e;
                exit;
            }
        }


    }
?>
