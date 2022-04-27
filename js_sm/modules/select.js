var select = (function() {
	"use strict"

	var this_module = "select",
		module_upper = "Select",
		module_one = "select",
		apiDataAll = {
			controller: module_upper,
			methods: {
            	['all_' + this_module]: '',
            	'json': ''
            },
		},
		apiDataAllFilter = {
			controller: module_upper,
			methods: {
            	['all_filter_' + this_module]: { data: ''},
            	'json': ''
            },
		},
		apiDataLate = {
			controller: module_upper,
			methods: {
				'estados': '',
				'municipios': '',
				'localidades': '',
            	'json': ''
            },
		},
		apiDataForm = {
			controller: module_upper,
			methods: {
            	['add-' + module_one]: { data: ''},
            	//'add-animal': 1,
            	'json': ''
            },
		},
		apiDataUp = {
			controller: module_upper,
			methods: {
            	['update-' + module_one]: { data: ''},
            	'json': ''
            },
		},
  		headerVal =	[//qué y como se mostrará en la tabla de datos
			{"name":"id","title":"Id","visible": false, "style":{"width":50,"maxWidth":50}},
			{"name":"data_edit","type": "object", "visible": false},//Este edita, se encarga de editar
			{"name":"base","title":"Estrato", "style":{"width":150,"maxWidth":150}},
			{"name":"direccion","title":"Límite inferior", "style":{"width":150,"maxWidth":150}},
			{"name":"activo","title":"Límite superior","style":{"width":125,"maxWidth":125}},
			{"name":"fecha_registro","title":"Grado de vulnerabilidad","breakpoints":"all","style":{"width":125,"maxWidth":125}},
			
			{"name":"editar","title":"Editar","style":{"width":80,"maxWidth":80}}
		],
		all_data_tab,
		all_estados,
		all_municipios,
		all_localidades;

	var getInitResponse = function(json) {
		console.log("getinit");
		initMod.apiCall(json).then(function(res){
			console.log("res de nuestro nuevo modulo" + module_upper);
			console.log(res);
			all_data_tab = res.vulnerabilidad;

			var $modal = $('#editor-modal'),
				$editor = $('#editor'),
				$editorSend = $('#btn-guardar'),
				$editorTitle = $('#editor-title'),
				ft = FooTable.init('#footable-list', {
					"toggleColumn": "last",
					"columns": headerVal,
					"rows": all_data_tab,
					editing: {
						"addText": "Agregar registro",
						"alwaysShow": true,
						"allowAdd": false,
						"allowEdit": false,
						"allowDelete": false,
						enabled: false,
						addRow: function(){
							console.log("addrow click");
							$modal.removeData('row');
							$editor[0].reset();
							$modal.modal('show');
						},
						editRow: function(row){
							console.log("editrow click");
							var values = row.val();
							var ed = values.data_edit;
							$editor.find('#base').val(values.base);
							$editor.find('#id_base').val(values.id_base);
							$editor.find('#direccion').val(values.direccion);
							$("input[name=activo][value="+values.activo+"]").prop('checked', true);

							$modal.data('row', row);
							$editorTitle.text('Editar información del ' + module_one);
							$modal.modal('show');
							$('html,body').animate({scrollTop: 200}, 1000);
						}
					}
				},function(ft){
			        console.log("lemon tree");
			        $('table.footable>tbody>tr').addClass("testerman");
			        //initMod.setRealTimeReq('animales', res['last-update'], res['number-records'], res['last-id'], ft);
			    });

			$modal.modal({
			  	focus: false,
			  	show: false
			});

			$editorSend.on('click', function(e){
				//if (this.checkValidity && !this.checkValidity()) return;
				e.preventDefault();
				var res = $("#editor :input").validations();
				var row = $modal.data('row');

				if(res.errors() == 0) {
			  		var l = $(this).ladda();
			  		l.ladda( 'start' );
			  		var formData =  initMod.objectifyForm($("#editor").serializeArray());
			    	//apiDataForm.methods['add-animal']['data'] = formData;
			    	var formDataAll = new FormData;

					//formDataAll.append('entrada_pdf', $('.file_pdf')[0].files[0]); //si se requiere un pdf
					/*jQuery.each(jQuery('.file_pdf'), function(i, value) {
					    formDataAll.append('documento_'+i, value.files[0]);
					});*/

					/*var arr = {}, $select = $("#select-anexo"), name = $select.attr("name"), ai = 0;
					$select.find("option").each(function() {
					    arr['anexo' + ai] = this.value;
					    ai++;
					});*/
					
					//formData.id_tipo_anexo = arr;

			  		if (row instanceof FooTable.Row){//If update
			  			apiDataUp.methods['update-' + module_one]['data'] = formData;
			  			initMod.getFormData(formDataAll, apiDataUp, 'form');
						console.log("if instanceof casio");
						//row.val(values);
						initMod.apiCallAlter(formDataAll).then(function(res){
							if (initMod.validateNoAccessUser(res)) return;
							console.log("res");
							console.log(res);
							l.ladda( 'stop' );
							if (res['data-success'] == 'Done') {
								var res_row = res[module_one +'-up'][0];
								//ft.rows.add(res_row);
								row.val(res_row, false);
								$modal.modal('hide');
								/*$modal.on('hidden.bs.modal', function (e) {
									console.log("opeth hidden");
									$('table.footable>tbody>tr').addClass("testerman");
								})*/
								//initMod.setRealTimeReq('entrada', res_row.fecha_actualizacion, res['number-records'], res['last-id'], ft);
							}else {
								$editorSend.attr({'class': "btn-error-connect",'disabled': 'disabled'}).html("Error en el servidor, reinicie la página");
							}
						}, function(reason, json){
							l.ladda( 'stop' );
							$editorSend.attr({'class': "btn-error-connect",'disabled': 'disabled'}).html("Error en el servidor, reinicie la página");
							initMod.debugThemes(reason,json);
						});
					} else {//else add
						apiDataForm.methods['add-' + module_one]['data'] = formData;
						initMod.getFormData(formDataAll, apiDataForm, 'form');
						console.log("else instanceof casio");
						initMod.apiCallAlter(formDataAll).then(function(res){
							if (initMod.validateNoAccessUser(res)) return;
							console.log("res de add");
							console.log(res);
							l.ladda( 'stop' );
							if (res['data-success'] == 'Done') {
								var res_row = res[module_one + '-add'][0];
								ft.rows.add(res_row);
								$modal.modal('hide');
								/*$modal.on('hidden.bs.modal', function (e) {
									console.log("opeth hidden");
									$('table.footable>tbody>tr').addClass("testerman");
								})*/
								//initMod.setRealTimeReq('animales', res_row.fecha_actualizacion, res['number-records'], res['last-id'], ft);
							}else {
								$editorSend.attr({'class': "btn-error-connect",'disabled': 'disabled'}).html("Error en el servidor, reinicie la página");
							}
						}, function(reason, json){
							l.ladda( 'stop' );
							$editorSend.attr({'class': "btn-error-connect",'disabled': 'disabled'}).html("Error en el servidor, reinicie la página");
							initMod.debugThemes(reason,json);
						});
						/*values.id = uid++;
						ft.rows.add(values);*/
					}
		       	}
			});

		}, function(reason, json){
			console.log("non");
		 	initMod.debugThemes(reason, json);
		});
	}

	var selectEstado = function(x) {
		var indata = $.map(all_estados, function( item ) {
            return {
             	label: item.nomgeo,
	            value: item.cve_ent,
	            //telefono: item.telefono
            }
        });
        $("#select-estado").autocomplete({
	      	minLength: 0,
	      	source: indata,
	      	select: function( event, ui ) {
	      		if (ui.item.value > 0) {
	      			$("#select-municipio").prop("disabled", false);
	      			selectMunicipio(ui.item.value);
	      		}
		        $("#select-estado").val( ui.item.label );
		        $("#select-estado-id").val( ui.item.value );
		        return false;
	      	},
	      	change: function( event, ui ) {
	      		if (ui.item == null) {
	      			$("#select-estado-id").val("");
	      		}
	      	},
	      	close: function( event, ui ) {
	      		if ($("#select-estado").val() == "") {
	      			$("#select-estado-id").val("");
	      		}
	      	}
	    })
	    .autocomplete( "instance" )._renderItem = function( ul, item ) {
	      	return $( "<li>" )
	        //.append( "<div>" + item.label + "<br>" + item.telefono + "</div>" )
	        .append( "<div>" + item.label + "</div>" )
	        .appendTo( ul );
	    };
	    if (typeof x !== 'undefined') {
	    	$("#select-estado").data('ui-autocomplete')._trigger('select', 'autocompleteselect', {item:{value:x}});
	    }
    }

    var selectMunicipio = function(x) {
		var indata = $.map(all_municipios, function( item ) {
			if (x == item.cve_ent) {
				return {
	             	label: item.nomgeo,
		            value: item.cve_mun,
	            }
			}
        });
        $("#select-municipio").autocomplete({
	      	minLength: 0,
	      	source: indata,
	      	select: function( event, ui ) {
	      		if (ui.item.value > 0) {
	      			$("#select-localidad").prop("disabled", false);
	      			selectLocalidad(ui.item.value);
	      		}
		        $("#select-municipio").val( ui.item.label );
		        $("#select-municipio-id").val( ui.item.value );
		        return false;
	      	},
	      	change: function( event, ui ) {
	      		if (ui.item == null) {
	      			$("#select-municipio-id").val("");
	      		}
	      	},
	      	close: function( event, ui ) {
	      		if ($("#select-municipio").val() == "") {
	      			$("#select-municipio-id").val("");
	      		}
	      	}
	    })
	    .autocomplete( "instance" )._renderItem = function( ul, item ) {
	      	return $( "<li>" )
	        //.append( "<div>" + item.label + "<br>" + item.telefono + "</div>" )
	        .append( "<div>" + item.label + "</div>" )
	        .appendTo( ul );
	    };
	    if (typeof x !== 'undefined') {
	    	$("#select-municipio").data('ui-autocomplete')._trigger('select', 'autocompleteselect', {item:{value:x}});
	    }
    }

    var selectLocalidad = function(x) {
		var indata = $.map(all_localidades, function( item ) {
            if (x == item.cve_mun) {
				return {
	             	label: item.nom_loc,
		            value: item.CGLOC,
	            }
			}
        });
        $("#select-localidad").autocomplete({
	      	minLength: 0,
	      	source: indata,
	      	select: function( event, ui ) {
	      		if (ui.item.value > 0) {
	      			//selectAutoDirec(x, ui.item.value);
	      		}
		        $("#select-localidad").val( ui.item.label );
		        $("#select-localidad-id").val( ui.item.value );
		        return false;
	      	},
	      	change: function( event, ui ) {
	      		if (ui.item == null) {
	      			$("#select-localidad-id").val("");
	      		}
	      	},
	      	close: function( event, ui ) {
	      		if ($("#select-localidad").val() == "") {
	      			$("#select-localidad-id").val("");
	      		}
	      	}
	    })
	    .autocomplete( "instance" )._renderItem = function( ul, item ) {
	      	return $( "<li>" )
	        //.append( "<div>" + item.label + "<br>" + item.telefono + "</div>" )
	        .append( "<div>" + item.label + "</div>" )
	        .appendTo( ul );
	    };
	    if (typeof x !== 'undefined') {
	    	$("#select-localidad").data('ui-autocomplete')._trigger('select', 'autocompleteselect', {item:{value:x}});
	    }
    }

	var initAlterData = function() {
		initMod.apiCall(apiDataLate).then(function(res){
			console.log("res alter");
			console.log(res);
        	all_municipios = res.municipios;
        	all_localidades = res.localidades;
        	all_estados = res.estados;

        	selectEstado();


        }, function(reason, json){
			console.log("non");
		 	initMod.debugThemes(reason, json);
		});
	}

	var buscarRes = function() {

		apiDataAllFilter.methods['all_filter_' + this_module]['data'] = {id: $("#select-localidad-id").val()}
		getInitResponse(apiDataAllFilter);//
	}

	var bindFilters = function() {
		//$(document).on('click','.footable-edit', preventEdit);
        //$(document).on('click','#update-animal', editSection);
        //$(document).on('click','#animales-list>tbody>tr:not([data-expanded]):not(.footable-detail-row)', triggerFooRow);
        $("#btn-buscar").on("click", buscarRes);
        /*inputFile.on("change", initMod.chooseFileChange);
        $(document).on('click','.rem-file', initMod.remFile)*/
    };

	var init = function () {
		console.log("opeth");
		bindFilters();
        initAlterData();
    };

	return {
	    init : init,
	    this_module: this_module
	}
})();