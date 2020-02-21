<?php

		/*
		$result = mysql_query($sql, $connect);
		while ($row = mysql_fetch_assoc($result)){
			$count = $row["count"];
		}
		*/

		$count = $totalCount;

		$last_page = ceil($count/$pageSize);

		if($pageNum != 1){
		?>
		<a href="<?=$pageName?>?pageNum=1<?=$param?>" class="btn_prev">처음</a>
		<!-- <li class="paginate_button page-item previous" ><a class="page-link" href="<?=$pageName?>?pageNum=1<?=$param?>"> ≪ </a></li> -->
		<?
		}else{
			?>
			<a href="#none" class="btn_prev dis">처음</a>
			<!-- <li class="paginate_button page-item previous" ><a class="page-link" href="<?=$pageName?>?pageNum=1<?=$param?>"> ≪ </a></li> -->
			<?
		}

		if ( $pageNum > 10 )
		{
			$prev_page = $pageNum - $pageNum%10 - 9;
			?>
			<!-- <li class="paginate_button page-item previous" id="dataTable_previous" ><a class="page-link" href="<?=$pageName?>?pageNum=<?=$prev_page?><?=$param?>"> ＜ </a></li> -->
			<a href="<?=$pageName?>?pageNum=<?=$prev_page?><?=$param?>" class="btn prv">이전</a>
		<?
		}
		if($pageNum % 10 == 0)
		{
			$isNoremainder = true;
		}
		else {
			$isNoremainder = false;
		}
		$first =(int)( $pageNum / 10);

		if ( $first < 1)
			$first = 1;
		else
			$first = $first * 10+1;

		if($isNoremainder)
			$first -= 10;


		for( $i = $first; $i< $first+10 ; $i++)
		{
			if ( $i > $last_page)
				break;

			if ( $i == $pageNum ){
				?><strong><?=$i?></strong><?
			}else{
				?><a href="<?=$pageName?>?pageNum=<?=$i?><?=$param?>"><?=$i?></a><?
			}
		}
		if ( $i <= $last_page ){
			$next_page = ceil($pageNum/10)*10+1;
			?>
			<!-- <li class="paginate_button page-item next" id="dataTable_next" ><a class="page-link" href="<?=$pageName?>?pageNum=<?=$next_page?><?=$param?>"> ＞ </a></li> -->
			<a href="<?=$pageName?>?pageNum=<?=$next_page?><?=$param?>" class="btn nxt">다음</a>
		<?
		}

		if($pageNum != $last_page){
		?>
		<!-- <li class="paginate_button page-item next" id="dataTable_next" ><a class="page-link" href="<?=$pageName?>?pageNum=<?=$last_page?><?=$param?>"> ≫ </a></li> -->
		<a href="<?=$pageName?>?pageNum=<?=$last_page?><?=$param?>" class="btn_next">마지막</a>
		<?
		}else{
			?>
			<!-- <li class="paginate_button page-item next" id="dataTable_next" ><a class="page-link" href="<?=$pageName?>?pageNum=<?=$last_page?><?=$param?>"> ≫ </a></li> -->
			<a href="#none" class="btn_next dis">마지막</a>
			<?
		}
?>