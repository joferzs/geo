<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if !IE]><!-->
<html lang="es">
<!--<![endif]-->
	<head>
		<meta charset="utf-8" />
		<title>vcxz / vcxz</title>
		<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
		<meta content="" name="description" />
		<meta content="" name="author" />
        <link rel="shortcut icon" href="images/faviconSIM"/>

	    <link href="css_sm/plugins/font-awesome/css/font-awesome.css" rel="stylesheet">
        <link href="css_sm/plugins/style.css" rel="stylesheet" type="text/css">
        <link href="css_sm/plugins/ladda/ladda-themeless.min.css" rel="stylesheet">
        <link href="css_sm/plugins/template/bootstrap.min.css" rel="stylesheet">
        <link href="css_sm/plugins/template/style-design-darks.css" rel="stylesheet" type="text/css">
        <link href="css_sm/plugins/validations/validations.css" rel="stylesheet">
	</head>
	<body class="pace-top bg-white">
		<div id="page-container" class="fade show">
			<div class="login login-with-news-feed">
				<div class="news-feed">
					<img class="mySlides w3-animate-right" src="images/login-img/login-1.jpg">
					<img class="mySlides w3-animate-right" src="images/login-img/login-2.jpg">
					<img class="mySlides w3-animate-right" src="images/login-img/login-3.jpg">
					<div class="news-caption">
						<h4 class="caption-title"><b>vcxz</b></h4>
						<p class="sub-caption-title">
							Sistema Integral vcxz
						</p>
						<img src="images/logo.png" width="105px" height="51px" class="login-footer-img">
					</div>
				</div>
				<div class="right-content">
					<div class="login-header">
						<div class="brand">
							<span class=""><img class="logo-login" src="images/logo.png"></span> <b>Bienvenido</b> 
							<small>Login</small>
						</div>
					</div>
					<div id="result-user"></div>
					<div class="login-content">
						<form class="margin-bottom-0" id="login-form">
							<div class="form-group m-b-15">
								<input type="text" name="usuario" class="form-control form-control-lg" placeholder="Usuario" required />
							</div>
							<div class="form-group m-b-15">
								<input type="password" name="password" class="form-control form-control-lg" placeholder="Password" required />
							</div>
							<div class="login-buttons">
								<input type="hidden" name="token_control_doc" id="data-token-controlDoc"/>
								<button type="button" class="btn btn-success btn-block btn-lg btn-login-form-admin" value="Iniciar" data-style="zoom-in">Iniciar sesión</button>
							</div>
							<hr />
						</form>
					</div>
					<div class="foot-title">
						<p class="text-center text-grey-darker">
							&copy; Sistemas vcxz <strong> 2022-<?= date('Y') ?></strong>
						</p>
					</div>
				</div>
			</div>
		</div>
		<script src="js_sm/jquery-3.1.1.min.js"></script>
		<script src="js_sm/plugins/validations/validations.js"></script>
		<script src="js_sm/plugins/ladda/spin.min.js"></script>
	    <script src="js_sm/plugins/ladda/ladda.min.js"></script>
	    <script src="js_sm/plugins/ladda/ladda.jquery.min.js"></script>
		<script type="text/javascript">
			var myIndex = 0;
			carousel();

			function carousel() {
			  	var i;
			  	var x = document.getElementsByClassName("mySlides");
			  	for (i = 0; i < x.length; i++) {
			    	x[i].style.display = "none";  
			  	}
			  	myIndex++;
			  	if (myIndex > x.length) {myIndex = 1}    
			  	x[myIndex-1].style.display = "block";  
			  	setTimeout(carousel, 8000);    
			}

		    var formLogin = (function() {
				"use strict"

				var apiJson = {
						controller: 'SessionSecurity',
						methods: {
			            	'validate-user': '',
			            	'json': ''
			            },
					},
					apiFormJson = {
						controller: 'SessionSecurity',
						methods: {
			            	'validate-login':  { data: ''},
			            	'json': ''
			            },
					},
					form = $("#login-form"),
					btnLogin = $(".btn-login-form-admin"),
					def = $.Deferred(),
					p = def.promise();

				var getInitResponse = function() {
					apiCall(apiJson).then(function(res){
						var userData = res['session-user'];
						if (userData.status) {
							location.href = "index.php";
						}else {
							$("#data-token-controlDoc").val(userData.tkn)//cambiar "#data-token-controlDoc" de acuerdo a la aplicación
						}
					}, function(reason, json){
					 	debugThemes(reason, json);
					});
				}

				var apiCall = function(data) {
			        return $.ajax({
			            url: 'ApiControl/index.php',//si se requiere usar GET, cambiar ApiControlDocumentos/index.php de acuerdo a la carpeta de la aplicación ej. ApiControlSoporte
			            type: 'GET',
			            dataType: 'json',
						data: data
			        });
			    };

			    var apiCallPost = function(data) {
			        return $.ajax({
			            url: 'ApiControl/index.php',//si se requiere usar POST, cambiar ApiControlDocumentos/index.php de acuerdo a la carpeta de la aplicación ej. ApiControlSoporte
			            type: 'POST',
			            dataType: 'json',
						data: data
			        });
			    };

			    var sendDataLogin = function() {
				  	var formData =  objectifyForm(form.serializeArray());
			    	apiFormJson.methods['validate-login']['data'] = formData;
			    	var formDataAll = {form: apiFormJson};
				  	var res = $("#login-form :input").validations();
				  	if(res.errors() == 0){
				  		var l = $( this ).ladda();
				  		l.ladda( 'start' );
				  		apiCallPost(formDataAll).then(function(res){
							var userData = res['session-user'];
							if (userData.status) {
								location.href = "index.php";
							}else {
								l.ladda('stop');
								var msg = "El usuario o contraseña que ingresaste es incorrecto";
								$("#result-user").show(200).html(msg);
				            	$(".succes-form").addClass("error-form");
							}
						}, function(reason, json){
						 	debugThemes(reason, json);
				            l.ladda('stop');
				            var msg = "Error en el servidor, intente recargando la página o contacte al administrador";
							$("#result-user").show(200).html(msg);
			            	$(".succes-form").addClass("error-form");
						});
			       	}else{
			       		return false;
			       	}
				}

				var getFormData = function(form_data, values, name) {
				  	if(!values && name)
				        form_data.append(name, '');
				    else{
				        if(typeof values == 'object'){
				        	var key;
				            for(key in values){
				                if(typeof values[key] == 'object')
				                    getFormData(form_data, values[key], name + '[' + key + ']');
				                else {
				                	if (typeof name !== 'undefined')
				                    form_data.append(name + '[' + key + ']', values[key]);
				                }
				            }
				        }else
				            form_data.append(name, values);
				    }
				    return form_data;
				}

				var objectifyForm = function(formArray) {//serialize data function
				  	var returnArray = {};
				  	for (var i = 0; i < formArray.length; i++){
				    	returnArray[formArray[i]['name']] = formArray[i]['value'];
				  	}
				  	return returnArray;
				}

			    var debugThemes = function(x, y) {
			    	console.log("Error en la peticion", x);
					console.log("Json enviado", y);
			    }


			    var bindTheme = function() {
			        btnLogin.on("click", sendDataLogin);
			    };

				var init = function () {
					getInitResponse();
					bindTheme();
					//initModule();
			    };

				return {
				    init : init
				}
			})();

			$(document).ready(formLogin.init);
		</script>
	</body>
</html>
