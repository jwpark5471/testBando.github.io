
<?php 
	include_once($_SERVER[DOCUMENT_ROOT]."/_lib/common.php");
	include '../include/header.php';
	  $nowGNB = 'recruit';
	  
?>
<body>
<div id="wrap" class="application">
<?php include '../include/gnb.php';?>

<script src="./js/application.js"></script>
<script type="text/javascript">

function cancelMessage(){
	alert("행사가 취소되었습니다.");
}

</script>


	<div id="container">
	<? include '../include/popup.php';?>
		<div id="sub_visual"></div><!-- sub_visual -->
		
		<h2 class="title_type_01">참가안내</h2>
		<section>
			<div class="tab_type_01">
				<ul class="tab_btn">
					<li <?php if($formType == 'company' || $formType == ''){ echo 'class="on"'; }?>><a href="#app_company">기업참가신청</a></li>
					<li <?php if($formType == 'view'){ echo 'class="on"'; }?> ><a href="#app_exhibition">전시관람신청</a></li>
				</ul><!-- tab_btn -->
				<form id="fileUploadForm" action="" name="fileUploadForm" enctype="multipart/form-data">
					<div class="tab_cont on" id="app_company">
						<h3 class="title_type_02">2020 미세먼지 EXPO 전시부스 신청안내</h3>
						<ul class="list_dot">
							<li>
								<em>신청기간 : </em>
								<p>신청 마감</p>
							</li>
							<li>
								<em>신청방법 : </em>
								<p>홈페이지 접수 후 신청서 제출 및 이메일 제출</p>
							</li>
							<li>
								<em>전시대상 : </em>
								<p>측정기 분야 및 저감기술 분야, 기타 미세먼지 관련 제품 등 [간이측정기 부분은 환경부 인증제품(1~3등급)만 신청가능]</p>
							</li>
							<li>
								<em>전시방법 : </em>
								<p>부스(3M × 3M) 활용 전시</p>
							</li>
							<li>
								<em>선정방법 : </em>
								<p>- 접수 마감 후 자체 평가를 통한 전시업체 및 전시품목 선정<br>
								※ 행사 성격, 세션별 부스 규모 및 제품 우수성 등을 평가하여 자체 선정<br>
								- 결과발표는 홈페이지 및 개별 공지</p>
							</li>
							<li class="type_01">
								<em>선정업체 특전 </em>
								<p>- 전시 참가비 및 부스비 무료<br>
								※ 단 부스 내 제품 설치 및 부스 사용에 따른 부대비용은 업체 부담</p>
							</li>
						</ul><!-- list_dot -->

						<h3 class="title_type_02">2020 미세먼지 EXPO 기본부스 제공사항</h3>
						<div class="img"><img src="../resources/images/application_img_01.jpg" alt="" /></div>
						<!--
						<div class="btn_download">
							<a href="/fileDown.php?fileNm=han">참가신청서다운로드(한글)</a>&emsp;&emsp;&emsp;
							<a href="/fileDown.php?fileNm=word">참가신청서다운로드(워드)</a>
						</div><!-- btn_wrap -->
						<!--
						<fieldset>
							<div class="table_form">
								<table>
									<caption>기업참가신청폼</caption>
									<colgroup>
										<col style="width:194px" />
										<col />
									</colgroup>
									<tbody>
										<tr>
											<th scope="col"><label for="c_name">기업명<br />(Company)</label></th>
											<td><input type="text" class="inp_txt" id="c_name" name="c_name" style="width:500px;" disabled /></td>
										</tr>
										<tr>
											<th scope="col">사업분야<br />(Business field)</th>
											<td>
												<ul class="list_radio" > 
													<li><span class="radio_ui"><input type="radio" id="c_business1" name="c_business" value="측정기" disabled /><label for="c_business1">측정기</label></span></li>
													<li><span class="radio_ui"><input type="radio" id="c_business2" name="c_business" value="간이측정기" disabled /><label for="c_business2">간이측정기</label></span></li>
													<li><span class="radio_ui"><input type="radio" id="c_business3" name="c_business" value="저감기술" disabled /><label for="c_business3">저감기술</label></span></li>
													<li><span class="radio_ui"><input type="radio" id="c_business4" name="c_business" disabled /><label for="c_business4">기타</label></span>&nbsp;( <input type="text" class="inp_txt" id="c_business_etc" style="width:200px;" disabled /> )</li>
												</ul>
											</td>
										</tr>
										<tr>
											<th scope="col"><label for="c_manager">이름<br />(Name)</label></th>
											<td><input type="text" class="inp_txt" id="c_manager" name="c_manager" style="width:500px;" disabled />
											</td>
										</tr>
										<tr>
											<th scope="col"><label for="c_phone1">핸드폰<br>(Phone)</label></th>
											<td>
												<select id="c_phone1" name="c_phone1" class="select_ui" style="width:160px;" disabled>
													<option value="010">010</option>
													<option value="011">011</option>
													<option value="016">016</option>
													<option value="017">017</option>
													<option value="018">018</option>
													<option value="019">019</option>
												</select> &nbsp;
												<input type="text" class="inp_txt" id="c_phone2" name="c_phone2" style="width:160px;" maxlength="4" numberOnly="true" disabled /> &nbsp;
												<input type="text" class="inp_txt" id="c_phone3" name="c_phone3" style="width:160px;" maxlength="4" numberOnly="true" disabled />
											</td>
										</tr>
										<tr>
											<th scope="col">신청서 첨부(application form)</th>
											<td>
												<div class="file_ui">
													<input type="text" class="file_text" readonly="readonly" title="첨부된 파일경로" disabled />
													<span class="file_wrap">찾아보기
														<input type="file" class="file_add" id="c_apply_form" name="c_apply_form" disabled />
													</span>
													<button class="btn_del" type="button">삭제</button>
												</div><!--file_ui-->
												<!--
												<p class="txt_01">* 반드시 참가신청서 다운로드 후 첨부하여만 접수가 완료됩니다.</p>
											</td>
										</tr>
									</tbody>
								</table>
							</div><!-- table_form -->
							
							<!--
							<div class="agree_wrap">
								<h4>개인정보 수집 및 이용에 관한 안내 동의  Collection and use of personal information.</h4>
								<p>2020 미세먼지 EXPO 주최측 및 사무국은 참석자 본인 확인 등 행사참석을 목적으로 ‘개인정보보호법 제15조(개인정보의 수집 및 이용)’에의거하여 최소한의 개인정보만을 수집하고 있으며, 수집한 개인정보는 본 수집 및 이용 목적외의 다른 목적으로는 사용되지 않습니다. 수집되는 개인정보의 보유기간은 행사 종류 후 1개월까지이며, 보유기간 종료 이후에는 지체없이 파기됩니다. 개인정보에 대한 수집 및 이용에 거부할 수 있으나 미동의시 행사 참석이 제한 될 수 있습니다. </p>
								<ul class="list_radio">
									<li><span class="radio_ui"><input type="radio" id="c_agree" name="c_agree" value="Y" /><label for="c_agree">동의(Agree)</label></span></li>
									<li><span class="radio_ui"><input type="radio" id="c_disagree" name="c_agree" value="N" /><label for="c_disagree">동의하지 않음(Disagree)</label></span></li>
								</ul>
							</div><!-- agree_wrap -->
							<!--	
							<div class="btn_wrap">
								<button type="button" class="btn" id="companyBtn">신청하기</button>
							</div><!-- btn_wrap -->
						<!--
						</fieldset>-->
					</div><!-- 기업참가신청 -->
				</form>
				<div class="tab_cont" id="app_exhibition">
					<h3 class="title_type_02">전시관람신청</h3>

					<div class="app_step">
						<p>전시관람 신청을 하신 후 관람당일 현장에서 신청 확인 후 바로 입장 가능합니다.</p>
						<ol>
							<li>온라인 신청</li>
							<li>현장 확인</li>
							<li>입장</li>
						</ol>
					</div><!-- app_step -->

					<fieldset>
						<div class="table_form">
							<table>
								<caption>전시관람신청폼</caption>
								<colgroup>
									<col style="width:194px" />
									<col />
								</colgroup>
								<tbody>
									<tr>
										<th scope="col"><label for="e_name">이름<br>(Name)</label></th>
										<td><input type="text" class="inp_txt" id="e_name" style="width:500px;" disabled /></td>
									</tr>
									<tr>
										<th scope="col"><label for="e_email1">이메일<br>(Email)</label></th>
										<td>
											<input type="text" class="inp_txt" id="e_email1" style="width:160px;" disabled /> @
											<input type="text" class="inp_txt" id="e_email2" style="width:317px;" disabled />
										</td>
									</tr>
									<tr>
										<th scope="col"><label for="e_phone1">핸드폰<br>(Phone)</label></th>
										<td>
											<select id="e_phone1" class="select_ui" style="width:160px;" disabled>
												<option value="010">010</option>
												<option value="011">011</option>
												<option value="016">016</option>
												<option value="017">017</option>
												<option value="018">018</option>
												<option value="019">019</option>
											</select> &nbsp; 
											<input type="text" class="inp_txt" id="e_phone2" style="width:160px;" maxlength="4" numberOnly="true" disabled /> &nbsp; 
											<input type="text" class="inp_txt" id="e_phone3" style="width:160px;" maxlength="4" numberOnly="true" disabled/>
										</td>
									</tr>
								</tbody>
							</table>
						</div><!-- table_form -->

						<div class="agree_wrap">
							<h4>개인정보 수집 및 이용에 관한 안내 동의  Collection and use of personal information.</h4>
							<p>2020 미세먼지 EXPO 주최측 및 사무국은 참석자 본인 확인 등 행사참석을 목적으로 ‘개인정보보호법 제15조(개인정보의 수집 및 이용)’에 의거하여 최소한의 개인정보만을 수집하고 있으며, 수집한 개인정보는 본 수집 및 이용 목적 외의 다른 목적으로는 사용되지 않습니다. 수집되는 개인정보의 보유기간은 행사 종류 후 1개월까지이며, 보유기간 종료 이후에는 지체없이 파기됩니다. 개인정보에 대한 수집 및 이용에 거부할 수 있으나 미동의시 행사 참석이 제한 될 수 있습니다. </p>
							<ul class="list_radio">
								<li><span class="radio_ui"><input type="radio" id="e_agree" name="e_agree" value="Y" /><label for="e_agree" disabled>동의(Agree)</label></span></li>
								<li><span class="radio_ui"><input type="radio" id="e_disagree" name="e_agree" value="N" /><label for="e_disagree" disabled>동의하지 않음(Disagree)</label></span></li>
							</ul>
						</div><!-- agree_wrap -->

						<div class="btn_wrap">
							<!--<button type="button" class="btn" id="viewBtn">신청하기</button>-->
							<button type="button" class="btn" onclick="cancelMessage();">신청하기</button>
						</div><!-- btn_wrap -->

					</fieldset>
				</div><!-- 전시관람신청 -->
			</div><!-- tab_type_01 -->
		</section>

	</div><!-- container -->

	<? include '../include/footer.php';?>

</div><!-- wrap -->








</body>
</html>