

	<header id="header">
		<div class="inner">
			<h1 class="logo"><a href="/view/main/main.php"><img src="/view/resources/images/logo.png" alt="2020 미세먼지 EXPO" /></a></h1>
			<nav id="gnb">
				<ul>
					<li <?php if($nowGNB == 'introduce'){ echo 'class="on"'; }?>><a href="/view/introduce/introduce.php">EXPO소개</a></li>
					<li <?php if($nowGNB == 'recruit'){ echo 'class="on"'; }?>><a href="/view/recruit/application.php">참가안내</a></li>
					<li <?php if($nowGNB == 'open_forum'){ echo 'class="on"'; }?>><a href="/view/open_forum/openForum.php">전문가토론회</a></li>
				</ul>
			</nav>
		</div>
	</header>
	<!-- header -->