
<?php include '../include/header.php';
	  $nowGNB = 'main';
	  
?>
<body>
<script type="text/javascript">
console.log("성ttttt공");

function companyBtn(){
	
	alert("접수가 마감되었습니다.");
	//location.href="/view/recruit/application.php#app_company";
	
	
}

function cancelMessage(){
	alert("행사가 취소되었습니다.");
}

</script>

<div id="wrap" class="main">
<?php include '../include/gnb.php';?>
	<div id="container">
		<section class="section01">
			<img src="/view/resources/images/main_img_01.jpg" alt="2020 미세먼지 EXPO" />
			<div class="btn_wrap">
				<a href="#none" onclick="companyBtn();">기업참가신청</a>
				<a href="#none" onclick="cancelMessage();">전시관람신청</a>
			</div>
		</section>
	</div><!-- container -->
	
	
<? include '../include/footer.php';?>
	
	<div class="noticepopup">
		<div class="pop_content">
			<img src="/view/resources/images/pop_notice_200130.jpg" alt="2020 미세먼지 EXPO 취소 안내" />
		</div>
		<a href="#none" class="btn_close_pop">닫기</a>
	</div><!-- popup -->

</div><!-- wrap -->

</body>
</html>
