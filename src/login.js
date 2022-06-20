"use strict";
const boton = document.getElementById("buttonRegistro");

boton.addEventListener("click", registrarUsuario);

const inputEmail = document.getElementById("emailSignup");
const inputPassword = document.getElementById("passwordSignup");
const inputCaptcha_valor = document.getElementById(
	"g-recaptcha-response"
).value;

grecaptcha.ready(function () {
	// do request for recaptcha token
	// response is promise with passed token
	grecaptcha
		.execute("6Ld-vlQgAAAAANx6tH9cFTC2T4tTvgbjW44BulFI", {
			action: "validate_captcha",
		})
		.then(function (token) {
			// add token value to form
			document.getElementById("g-recaptcha-response").value = token;
			checkRecaptcha();
		});
});

function checkRecaptcha() {
	let inputCaptcha_valor = document.getElementById(
		"g-recaptcha-response"
	).value;
	console.log(inputCaptcha_valor);
	if (inputCaptcha_valor != "") {
		boton.disabled = false;
		return true;
	}
	return false;
}

function encrypt(text) {
	return CryptoJs.AES.encrypt(text, "Secret Passphrase");
}

function registrarUsuario() {
	let inputEmail_valor = inputEmail.value;
	let inputPassword_valor = inputPassword.value;

	let emailBoolean = true;
	let passwordBoolean = true;

	if (inputEmail_valor == "" || !isNaN(inputEmail_valor)) {
		emailBoolean = false;
	}

	if (inputPassword_valor == "" || !isNaN(inputPassword_valor)) {
		passwordBoolean = false;
	}

	/*
	if (
			nameBoolean &&
			emailBoolean &&
			phoneBoolean &&
			passwordBoolean &&
			checkRecaptcha()
		) {
	
	}
	*/
	$.ajax({
		url: "./login.php",
		type: "POST",
		data: {
			api: "checkEmail",
			email: encrypt(inputEmail_valor),
			password: encrypt(inputPassword_valor),
			captcha: encrypt(document.getElementById("g-recaptcha-response").value),
		},
		dataType: "json",
		success: function (response) {
			if (response == 0) {
				console.warn(response);
			} else {
				console.log(response);
				if ("error" in response) {
					console.warn("ERROR");
					emailBoolean = false;
				} else {
					console.warn("OK");
					emailBoolean = true;
					crearCookie(response.success);
				}
				coloresCampos(emailBoolean, passwordBoolean);
			}
		},
		error: function (error) {
			console.warn("ERROR:" + error);
			emailBoolean = false;
			coloresCampos(emailBoolean, passwordBoolean);
		},
	});
}

function coloresCampos(emailBoolean, passwordBoolean) {
	if (emailBoolean) {
		inputEmail.classList.remove("inputError");
		inputEmail.classList.add("inputSuccess");
	} else {
		inputEmail.classList.remove("inputSuccess");
		inputEmail.classList.add("inputError");
	}

	if (passwordBoolean) {
		inputPassword.classList.remove("inputError");
		inputPassword.classList.add("inputSuccess");
	} else {
		inputPassword.classList.remove("inputSuccess");
		inputPassword.classList.add("inputError");
	}
}

function crearCookie(user) {
	user = JSON.parse(user);
	var exdays = 30;
	var d = new Date();
	d.setTime(d.getTime() + exdays * 24 * 60 * 60 * 1000);
	var expires = "expires=" + d.toUTCString();
	document.cookie = "email=" + "=" + user.email + "; " + expires;
	document.cookie = "nombre=" + "=" + user.nombre + "; " + expires;
	document.cookie = "token=" + "=" + user.token + "; " + expires;

	// console.log(getCookie("email"));
	// console.log(getCookie("nombre"));
	// console.log(getCookie("token"));

	window.location.replace("./index2.html");
}

function getCookie(cname) {
	let name = cname + "=";
	let decodedCookie = decodeURIComponent(document.cookie);
	let ca = decodedCookie.split(";");
	for (let i = 0; i < ca.length; i++) {
		let c = ca[i];
		while (c.charAt(0) == " ") {
			c = c.substring(1);
		}
		if (c.indexOf(name) == 0) {
			return c.substring(name.length, c.length);
		}
	}
	return "";
}
