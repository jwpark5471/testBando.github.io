
<div class="popup commonPopup" id="popup">
	<div class="pop_wrap">
		<div class="pop_inner">
			<div class="pop_box">
				<div class="pop_content">
					<div class="textbox" id="alert_value"></div>
				</div>
				<div class="pop_btn_wrap">
					<button class="btn" type="button">Cancel</button>
					<button class="btn close" type="button">Ok</button>
				</div>
				<button class="btn_close_pop" type="button">닫기</button>
			</div>
		</div>
	</div>
</div><!-- popup -->


<div class="popup popupRecruit" id="popup_recruit">
	<div class="pop_wrap">
		<div class="pop_inner">
			<div class="pop_box">
				<div class="pop_content">
					<div class="textbox">입력하신 정보로 신청하시겠습니까?</div>
				</div>
				<div class="pop_btn_wrap">
					<button class="btn" type="button">Cancel</button>
					<button class="btn close" type="button" onclick="viewApply('Y')">Ok</button>
				</div>
				<button class="btn_close_pop" type="button">닫기</button>
			</div>
		</div>
	</div>
</div><!-- popup -->


<div class="popup popupRecruitCompany" id="popup_recruit_company">
	<div class="pop_wrap">
		<div class="pop_inner">
			<div class="pop_box">
				<div class="pop_content">
					<div class="textbox">입력하신 정보로 신청하시겠습니까?</div>
				</div>
				<div class="pop_btn_wrap">
					<button class="btn" type="button">Cancel</button>
					<button class="btn close" type="button" onclick="companyApply('Y')">Ok</button>
				</div>
				<button class="btn_close_pop" type="button">닫기</button>
			</div>
		</div>
	</div>
</div><!-- popup -->


<div class="popup" id="popup_recruit_success">
	<div class="pop_wrap">
		<div class="pop_inner">
			<div class="pop_box">
				<div class="pop_content">
					<div class="textbox">신청이 완료되었습니다. 감사합니다.</div>
				</div>
				<div class="pop_btn_wrap">
					<button class="btn" type="button" onclick="location.href='<? $_SERVER[DOCUMENT_ROOT] ?>/view/main/main.php'">Cancel</button>
					<button class="btn close" type="button" onclick="location.href='<? $_SERVER[DOCUMENT_ROOT] ?>/view/main/main.php'">Ok</button>
				</div>
				<button class="btn_close_pop" type="button" onclick="location.href='<? $_SERVER[DOCUMENT_ROOT] ?>/view/main/main.php'">닫기</button>
			</div>
		</div>
	</div>
</div><!-- popup -->