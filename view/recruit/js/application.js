// ################ START onload ############
window.onload = function() {
}
// ################ END onload ############

// ################ START ready ############
$(document).ready(function() {
    $("#viewBtn").click(function(){
		viewForm();
		var formData = viewForm();
		if(!formData){
        return;
		}
		popupRecruit();
    })

    $("#companyBtn").click(function(){
        alert("접수가 마감되었습니다.");
		
		// companyForm();
		// var formData = companyForm();
		// if(!formData){
        // return;
		// }
		// popupRecruitCompany();
    })

});
// ################ END ready ############

// ################ START function ############
function viewForm(){
    var e_name = $("#e_name");
	var e_email1 = $("#e_email1");
	var e_email2 = $("#e_email2");
	var e_phone1 = $("#e_phone1");
	var e_phone2 = $("#e_phone2");
	var e_phone3 = $("#e_phone3");
	var e_agree = $("input[name=e_agree]:checked"); //radio
    
    if(e_name.val() == ""){
        popupOpen("이름을 입력하시기 바랍니다.");
        return false;
    }

    if(e_email1.val() == ""){
		popupOpen("이메일을 입력하시기 바랍니다.");
        return false;
    }
    if(e_email2.val() == ""){
        popupOpen("이메일을 입력하시기 바랍니다.");
        return false;
    }
	
	regExp = /^[0-9a-zA-Z]([-_\.]?[0-9a-zA-Z])/ ;
    if( !regExp.test( e_email1.val() ) ){
        popupOpen("이메일을 입력하시기 바랍니다.");
        return false;
    }
	
	if(e_phone2.val() == ""){
		popupOpen("핸드폰 번호를 입력하시기 바랍니다.");
        return false;
    }
    if(e_phone3.val() == ""){
        popupOpen("핸드폰 번호를 입력하시기 바랍니다.");
        return false;
    }
	
    
	if(e_agree.val() != "Y"){
        popupOpen("개인정보에 동의 해주시기 바랍니다.");
        return false;
    }

    var formA1 = {
          view_director_name : e_name.val()
        , view_director_email : e_email1.val() + "@" + e_email2.val()
        , view_director_phone : e_phone1.val()+e_phone2.val()+e_phone3.val()
        , view_agree_form : e_agree.val()
		, mode : "view"
    }

    return formA1;
}

function companyForm(){
    var c_name = $("#c_name");
	var c_business = $("input[name=c_business]:checked"); //radio
	var c_business_etc = $("#c_business_etc");
	var c_manager = $("#c_manager");
	var c_phone1 = $("#c_phone1");
	var c_phone2 = $("#c_phone2");
	var c_phone3 = $("#c_phone3");
	var c_agree = $("input[name=c_agree]:checked");
	var c_apply_form = $("#c_apply_form");
    
    if(c_name.val() == ""){
        popupOpen("기업명 정보를 입력하시기 바랍니다.");
        return false;
    }
	
    if(c_business.val() == "" || c_business.val() ==undefined){
		popupOpen("사업분야를 체크해 주시기 바랍니다.");
        return false;
    }
    if(c_manager.val() == ""){
        popupOpen("이름을 입력하시기 바랍니다.");
        return false;
    }

    if(c_phone2.val() == ""){
		popupOpen("핸드폰 번호를 입력하시기 바랍니다.");
        return false;
    }
    if(c_phone3.val() == ""){
        popupOpen("핸드폰 번호를 입력하시기 바랍니다.");
        return false;
    }
	
	
	if(c_apply_form.val() == ""){
        popupOpen("신청서를 첨부하여 주시기 바랍니다.");
        return false;
    }
	
	if(c_agree.val() != "Y"){
        popupOpen("개인정보에 동의 해주시기 바랍니다.");
        return false;
    }
	
	
	if(c_business.val() == '기타'){
		c_business.val() = "기타("+c_business_etc.val()+")";
	}
	

    var formA1 = {
          company_name : c_name.val()
        , company_business_type : c_business.val()
        , company_director_name : c_manager.val()
        , company_director_phone : c_phone1.val()+c_phone2.val()+c_phone3.val()
		, company_agree_form : c_agree.val()
		, mode : "company"
    }

    return formA1;
}


//팝업
function popupOpen(alertValue){
	$("#popup").show();
	$("#alert_value").html(alertValue);
}

function popupRecruit(){
	$("#popup_recruit").show();
}


function popupRecruitCompany(){
	$("#popup_recruit_company").show();
}

function viewApply(apply_answer){
	if(apply_answer != "Y"){
		return false;
	}
	viewAjax();
}

function companyApply(apply_answer){
	if(apply_answer != "Y"){
		return false;
	}
	
	companyAjax();
	
}

// 전화번호, 핸드폰번호 숫자만 입력
$(document).on("keyup", "input:text[numberOnly]", function() {$(this).val( $(this).val().replace(/[^0-9]/gi,"") );});

// ################ END function ############

// ################ START Ajax ############
//기본정보 등록
function viewAjax(){
    var formData = viewForm();
    if(formData === false){
        return;
    }
	
    $.ajax({
        url: './applicationProc.php',
        type: 'POST',
        data: formData,
        dataType: 'json',
        error:function(request,status,error){
			alert("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
	   },
        success: function(data) {
            console.log(data);
            if (data.result == '1') {
				$("#popup_recruit").hide();
                $("#popup_recruit_success").show();
            }else{
                alert(data.msg);
            }
        }
    });
}

//기본정보 등록
function companyAjax(){
	
	
	var form = $('#fileUploadForm')[0];
    var formData = new FormData(form);
    formData.append("c_apply_form",$('#c_apply_form').val());
	formData.append("mode","company");
	
	
    $.ajax({
        url: './applicationProc.php',
        type: 'POST',
		processData: false,
        contentType: false,
        data: formData,
        dataType: 'json',
        error:function(request,status,error){
			alert("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
	   },
        success: function(data) {
            console.log(data);
            if (data.result == '1') {
				$("#popup_recruit").hide();
                $("#popup_recruit_success").show();
            }else{
				
            }
        }
    });
}

// ################ END Ajax ############
