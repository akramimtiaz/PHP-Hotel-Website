function valUsername(value){
		var username = value;
		var reg = /^[A-Za-z0-9]{1}[A-Za-z0-9\-\_\']{1,19}$/;
		if(reg.test(username.value) == false){
			alert("invalid username, please enter a valid username.");
			username.focus();
	}
}

function valName(value){
		var name = value;
		var reg = /^[A-Za-z]{1}[A-Za-z\'\-]{0,}[\s]{0,1}[A-za-z\'\-]{1,}$/; 
		if(reg.test(name.value) == false || name.length>20){
			alert("invalid name, please enter a valid name.");
			name.focus();
	}
}

function valPassword(value){
		var password = value;
		var reg = /^.\S{6,20}$/; 
		if(reg.test(password.value) == false){
			alert("invalid password, please enter a valid password.");
			password.value = null;
			password.focus();
	}
}

function valMatching(value){
		var pw1 = document.getElementById("rform").pw1;
		var pw2 = value;

		if(pw1.value != pw2.value){
			alert("The passwords do not match, please try again.");
			pw2.value = null;
			pw1.value = null;
			pw1.focus();
	}
}


function valAddress(value){
		var address = value;
		var reg = /^[A-Za-z\d\']{1}[A-Za-z\d\s\'\/\,\.\-]{1,39}$/;
		if(reg.test(address.value) == false){
			alert("invalid address, please enter a valid address.");
			address.focus();
	}
}

function valPostcode(value){
		var pcode = value;
		var reg = /^[\d]{4}$/; 
		if(reg.test(pcode.value) == false){
			alert("invalid postcode, please enter a valid postcode.");
			pcode.focus();
	}
}


function valNumber(value){
		var mobile = value;
		var reg = /^04[\d]{8}$/; 
		if(reg.test(mobile.value) == false){
			alert("invalid mobile number, please enter a valid number.");
			mobile.focus();
	}
}

function valEmail(value){
		var email = value;
		var reg = /^[\w.-]+@[\w.-]+\.[A-Za-z]{2,6}$/;
		if(reg.test(email.value) == false || email.length>40){
			alert("invalid email, please enter a valid email.");
			email.focus();
	}
}

function resetForm(form){
	form.uname.value = null;
	form.gname.value = null;
	form.sname.value = null;
	form.pw1.value = null;
	form.pw2.value = null;
	document.getElementById("rform").address.value = null;
	form.state.value = "";
	form.postcode.value = null;
	form.mobile.value = null;
	form.email.value = null;
}




