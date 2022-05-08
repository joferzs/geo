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
				'temas': '',
				'subtemas': '',
				'indicadores': '',
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
  		headerVal/* =	[//qué y como se mostrará en la tabla de datos
			{"name":"id","title":"Id","visible": false, "style":{"width":50,"maxWidth":50}},
			{"name":"CGLOC","title":"CGLOC", "style":{"width":150,"maxWidth":150}},
			{"name":"FAD_0201","title":"FAD_0201", "style":{"width":150,"maxWidth":150}},
			{"name":"FAD_0202","title":"FAD_0202", "style":{"width":150,"maxWidth":150}},
			
			
			{"name":"editar","title":"Editar","style":{"width":80,"maxWidth":80}}
		]*/,
		all_data_tab,
		all_estados,
		all_municipios,
		all_localidades,
		all_temas,
		all_subtemas,
		all_indicadores,
		select_estado = $("#select-estado"),
		select_municipio = $("#select-municipio"),
		select_localidad = $("#select-localidad"),
		select_tema = $("#select-tema"),
		select_subtema = $("#select-subtema"),
		select_anio = $("#anio"),
		check_all = $("#check-all"),
		check_indicadores = $("#check-indicadores");

	var getInitResponse = function(json, l) {
		
		console.log("getinit");
		initMod.apiCall(json).then(function(res){
			console.log("res de nuestro nuevo modulo" + module_upper);
			console.log(res);

			all_data_tab = res.vulnerabilidad;

			if ($("#debug").val() == 'debug') {
				console.log(res.x);
				$(".res-x").html("send: " + JSON.stringify(res.x));
				$(".res-sql").html("sql: " + res.sql);
			}

			headerVal =	[//qué y como se mostrará en la tabla de datos
			]

			$.each(all_data_tab[0], function(i, v) {
				console.log("i");
				console.log(i);
				console.log("v");
				console.log(v);
		        //check_indicadores.append('<div><input type="checkbox" class="indicadores-check" name="indicadores-' + i + '" id="indicadores-' + i + '" value="' + v.value + '""> ' + v.label + '</div>')

		        headerVal.push({ "name": i,"title": i, "style":{"width":150,"maxWidth":150} });

		    });

		    console.log("headerVal");
		    console.log(headerVal);


			

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
			        console.log("lemon treeccc");
			        l.ladda( 'stop' );
			        //$('table.footable>tbody>tr').addClass("testerman");
			        //initMod.setRealTimeReq('animales', res['last-update'], res['number-records'], res['last-id'], ft);
			    });

			$modal.modal({
			  	focus: false,
			  	show: false
			});
		}, function(reason, json){
			console.log("non");
			l.ladda( 'stop' );
		 	initMod.debugThemes(reason, json);
		});
	}

	var selectEstado = function(x) {
	    $.each(all_estados, function(i, v) {
	    	console.log("v");
	    	console.log(v);
	        select_estado.append(new Option(v.NOMGEO, v.CVE_ENT));
	    });
    }

    var changeEstado = function() {
    	var value = $(this).val();
    	if (value != "") {
    		selectMunicipio(value);
    		select_municipio.prop("disabled", false);
    	}else {
    		select_municipio.prop("disabled", true);
    	}
    }

    var selectMunicipio = function(x) {
		var indata = $.map(all_municipios, function( item ) {
			if (x == item.CVE_ENT) {
				return {
	             	label: item.NOMGEO,
		            value: item.CVE_MUN,
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
	    }).focus(function () {
		    $(this).autocomplete("search");
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
            if (x == item.CVE_MUN) {
				return {
	             	label: item.NOM_LOC,
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
	    }).focus(function () {
		    $(this).autocomplete("search");
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

    var changeAnio = function() {
    	//selectSubtema(select_tema.val());
    }

    var selectTema = function(x) {
    	select_tema.html("");
        $.each(all_temas, function(i, v) {
	        select_tema.append(new Option(v.tema, v.cve_tem));
	    });

    }

    var changeTema = function(x) {
    	var value = $(this).val();
    	if (value != "") {
    		selectSubtema(value);
    		select_subtema.prop("disabled", false);
    	}else {
    		select_subtema.prop("disabled", true);
    	}
    	select_subtema.val(select_subtema.val()).trigger('change');
    }

    var selectSubtema = function(x) {
    	var anio_selected = select_anio.val();
		select_subtema.html("");
		console.log("all_subtemas");
		console.log(all_subtemas);
         $.each(all_subtemas, function(i, v) {

         	if (v.subtema != null) {
	         	var sub_anio = v.subtema.split(' ');
				var res_anio = sub_anio[sub_anio.length-1];
				/*console.log("res_aniozzzzzzzzz");
				console.log(res_anio);*/

		        //if (v.cve_tem == x && anio_selected == res_anio) {
		        if (v.cve_tem == x) {
		        	select_subtema.append(new Option(v.subtema, v.cve_sub));
		        }
		    }
	    });
    }

    var changeSubtema = function() {
    	var value = $(this).val();
    	if (value != "") {
    		indicadores(value);
    	}else {
    		//select_subtema.prop("disabled", true);
    	}
    	check_all.prop('checked', false);
    }

    var indicadores = function(x ="") {
	    var indata = $.map(all_indicadores, function( item ) {
			if (x == item.cve_sub) {
				return {
	             	label: item.indicadores,
		            value: item.cve_ind,
	            }
			}
        });
        check_indicadores.html("");
        check_indicadores.hide(300, function() {
			if (x != "") {
				$.each(indata, function(i, v) {
			        check_indicadores.append('<div><input type="checkbox" class="indicadores-check" name="indicadores-' + i + '" id="indicadores-' + i + '" value="' + v.value + '""> ' + v.label + '</div>')
			    });
			    check_indicadores.show(600);
			}
        });
    }

    var checkAllIndicadores = function() {
    	$('input:checkbox').not(this).prop('checked', this.checked);
    }

    var checkVisible = function() {
    	var one_true = false;
    	$('input[type=checkbox]:not(#check-all)').each(function () {
		    if (this.checked) one_true = true;
		});
		if (!one_true) check_all.prop('checked', false);
    }

	var initAlterData = function() {
		initMod.apiCall(apiDataLate).then(function(res){
			console.log("res alter a");
			console.log(res);
        	all_municipios = res.municipios;
        	all_localidades = res.localidades;
        	all_estados = res.estados;
        	all_temas = res.temas;
        	all_subtemas = res.subtemas;
        	all_indicadores = res.indicadores;

        	selectEstado();

        	selectTema();
        	select_tema.val(1).trigger('change');
        	

        	$(".load-data").hide(300, function() {
				$(".content-filters").show(600);
			});


        }, function(reason, json){
			console.log("non");
		 	initMod.debugThemes(reason, json);
		});
	}

	var buscarRes = function() {
		var l = $(this).ladda();
		l.ladda( 'start' );
		var sList = [];
		$('input[type=checkbox]:not(#check-all)').each(function () {
		    if (this.checked) {
		    	sList.push('"' + $(this).val() + '"');
		    }
		});
		console.log (sList);
		var in_end= sList.join(",");

		console.log("in_end");
		console.log(in_end);

		apiDataAllFilter.methods['all_filter_' + this_module]['data'] = {id_localidad: $("#select-localidad-id").val(), anio: $("#anio").val(), indicadores: in_end, debug: $("#debug").val()}
		getInitResponse(apiDataAllFilter, l);//
	}

	var bindFilters = function() {
        $("#btn-buscar").on("click", buscarRes);
        select_estado.on("change", changeEstado);
        select_anio.on("change", changeAnio);
        select_tema.on("change", changeTema);
        select_subtema.on("change", changeSubtema);
        check_all.on("click", checkAllIndicadores);
        $(document).on('click','.indicadores-check', checkVisible);
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