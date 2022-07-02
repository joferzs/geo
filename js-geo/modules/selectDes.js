var selectDes = (function() {
	"use strict"

	var this_module = "selectDes",
		module_upper = "SelectDes",
		module_one = "selectDes",
		apiDataAll = {
			controller: module_upper,
			methods: {
            	['all_' + this_module]: '',
            	'json': ''
            },
		},
		/*limit_in = 0,
		limit_data = 200,*/
		apiDataAllFilter = {
			controller: module_upper,
			methods: {
            	['all_filter_' + this_module]: { data: ""},
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
		apiDataLateFormat = {
			controller: module_upper,
			methods: {
				'estados_format': '',
				'municipios_format': '',
            	'json': ''
            },
		},
		apiDataLocalidades = {
			controller: module_upper,
			methods: {
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
		apiDataExcel = {
			controller: module_upper,
			methods: {
            	"export-excel":  { data: ""},
            	//'json': ''
            },
		},
		apiDataPdf = {
			controller: module_upper,
			methods: {
            	"export-pdf":  { data: ""},
            	//'json': ''
            },
		},
		apiDataExport = {
			controller: module_upper,
			methods: {
            	"export":  { data: ""},
            	'json': ''
            },
		},
		all_data_tab,
		all_estados,
		all_municipios,
		all_localidades,
		all_temas,
		all_subtemas,
		all_indicadores,
		all_estados_format,
		all_municipios_format,
		//nom_ind = {ID: "ID", CGLOC: "CGLOC", NOM_LOC : "LOCALIDAD", CVE_ENT : "estado", clave_estado : "clave estado", CVE_MUN : "municipio", clave_municipio : "clave municipio"},
		nom_ind = {ID: "ID", NOM_LOC : "LOCALIDAD"},
		select_estado = $("#select-estado"),
		select_municipio = $("#select-municipio"),
		select_localidad = $("#select-localidad"),
		select_tema = $("#select-tema"),
		select_subtema = $("#select-subtema"),
		select_subtema_id = $("#select-subtema-id"),
		select_anio = $("#anio"),
		btn_excel = $("#icono-excel"),
		btn_pdf = $("#icono-pdf"),
		btn_export = $("#icono-export"),
		check_all = $("#check-all"),
		check_indicadores = $("#check-indicadores");

	var limit_in,
		long_data = 10000,		//set max res for request
		post_resp;

	var limitConfig = function() {
		var jmet = apiDataAllFilter.methods;
		$.each(jmet, function(propName, propVal) {
		  	if (typeof propVal.data != 'undefined') {
		  		propVal.data.limit_in = limit_in;
		  		propVal.data.limit_data = long_data;
		  	}
		});
	}

	var getPostResponse = function(ft) {
		if (!post_resp) return;
		limit_in = limit_in + long_data;
		limitConfig();
		initMod.apiCall(apiDataAllFilter).then(function(res){
			all_data_tab = res.vulnerabilidad;
			if (all_data_tab.length > 0) {
				ft.rows.load(all_data_tab, true);
			}else {
				l.ladda( 'stop' );
				post_resp = false;
				/*btn_excel.show();
				btn_pdf.show();*/
				btn_export.show();
			}
		}, function(reason, json){
			console.log("err post");
			l.ladda( 'stop' );
		 	initMod.debugThemes(reason, json);
		});
	}

	var getInitResponse = function() {
		limit_in = 0;
		post_resp = true;

		limitConfig();

		initMod.apiCall(apiDataAllFilter).then(function(res){
			console.log("res de nuestro nuevo modulo" + module_upper);
			console.log(res);
			$(".res-error").hide();
			if ($("#debug").val() == 'debug') {
				$(".res-x").html("send: " + JSON.stringify(res.x));
				$(".res-sql").html("sql: " + res.sql);
			}

			var all_data_tab = res.vulnerabilidad, header = [], ii = 0;
			if (all_data_tab.length > 0) {
				$.each(all_data_tab[0], function(i, v) {
					var width = 100; 
					if (i == "ID") {
						width = 20;
					}
			        var yeison = { "name": i,"title": nom_ind[i], "style":{"width":width,"maxWidth":width} };
					/*if (i == "CVE_ENT") yeison.formatter = "select.getEstadoFormat";
					if (i == "CVE_MUN") yeison.formatter = "select.getMunicipioFormat";*/
			        if (ii > 4) yeison.breakpoints = "all";
			        header.push(yeison);
			        ii++;
			    });
			}else {
				header = [{ name: "id", title: "ID", "style":{"width":20,"maxWidth":20} }];
			}

			$('#footable-list').empty();    
			
			var ft = FooTable.init('#footable-list', {
				"columns": header,
				"rows": all_data_tab,
                'on': {
                    'postdraw.ft.table': function(e, ft) {
                    	console.log("kam");
                        getPostResponse(ft);
                    }
                }
			},function(ft){
				console.log("human fates");
		    });
		}, function(reason, json){
			console.log("non");
			l.ladda( 'stop' );
			if ($("#debug").val() == 'debug') {
				$(".res-error").html("Error msg: " + reason.responseText).show(1000);
			}else {
				$(".res-error").html("Error en la consulta").show(1000);
			}
		 	initMod.debugThemes(reason, json);
		});
	}

	var selectEstado = function(x) {
	    /*$.each(all_estados, function(i, v) {
	        select_estado.append(new Option(v.NOMGEO, v.CVE_ENT));
	    });*/

	    var i = 0, len = all_estados.length;
	    while (i < len) {
	    	console.log("esttta oo");
	    	console.log(all_estados[i]);
	       	select_estado.append(new Option(all_estados[i].NOMGEO, all_estados[i].CVE_ENT));
	        i++
	    }
    }

    var changeEstado = function() {
    	var value = $(this).val();
    	console.log("value");
	    console.log(value);
	    $("#select-municipio").val("");
	    $("#select-localidad").val("");
	    $("#select-localidad-id").val("");
	    select_localidad.prop("disabled", true);
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
	      			console.log("change select");
	      			console.log(ui.item.value);
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
	    	//$("#select-municipio").data('ui-autocomplete')._trigger('select', 'autocompleteselect', {item:{value:x}});
	    }
    }

    var availableTags = [
			"ActionScript",
			"AppleScript",
			"Asp",
			"BASIC",
			"C",
			"C++",
			"Clojure",
			"COBOL",
			"ColdFusion",
			"Erlang",
			"Fortran",
			"Groovy",
			"Haskell",
			"Java",
			"JavaScript",
			"Lisp",
			"Perl",
			"PHP",
			"Python",
			"Ruby",
			"Scala",
			"Scheme"
		];

    var selectLocalidad_ = function(x) {
    	$("#select-localidad").html("");
		var indata = $.map(all_localidades, function( item ) {
            if (x == item.CVE_MUN && select_estado.val() == item.CVE_ENT) {
				return {
	             	label: item.NOM_LOC,
		            value: item.CGLOC,
	            }
			}
        });
        $("#select-localidad").on( "keydown", function( event ) {
				if ( event.keyCode === $.ui.keyCode.TAB &&
						$( this ).autocomplete( "instance" ).menu.active ) {
					event.preventDefault();
				}
			}).autocomplete({
	      	minLength: 0,
	      	source: function( request, response ) {
					// delegate back to autocomplete, but extract the last term
					response( $.ui.autocomplete.filter(
						indata, extractLast( request.term ) ) );
				},
	      	//source: indata,
	      	select: function( event, ui ) {
	      		if (ui.item.value > 0) {
	      			//selectAutoDirec(x, ui.item.value);
	      		}
	      		console.log("this.valuesss");
	      		console.log(this.value);
	      		var terms = split( this.value );
				// remove the current input
				terms.pop();
				// add the selected item
				terms.push( ui.item.label );
				// add placeholder to get the comma-and-space at the end
				terms.push( "" );
				this.value = terms.join( ", " );
				return false;


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
	    	//$("#select-localidad").data('ui-autocomplete')._trigger('select', 'autocompleteselect', {item:{value:x}});
	    }
	    $("#select-localidad").prop("disabled", false);
    }

    select_localidad = $('#select-localidad');
    var selectLocalidad = function(x) {
    	var indata = $.map(all_localidades, function( item ) {
            if (x == item.CVE_MUN && select_estado.val() == item.CVE_ENT) {
				return {
	             	localidad: item.NOM_LOC,
		            id_localidad: item.CGLOC,
	            }
			}
        });
        $("#select-localidad").prop("disabled", false);
        var keys = ['id_localidad', 'localidad', select_localidad,indata];
        var selectForm = resetSelect(keys[2]);
        selectForm.selectize({
            plugins: ["remove_button"],
            delimiter: ",1349,",
            valueField: keys[0],labelField: keys[1],searchField: keys[1], options: keys[3],
            persist: false,
            create: false,
            sortField: "id_localidad",
            render: { option: function(item, escape) { return '<div><span class="name">' + escape(item[keys[1]]) + '</span></div>' } },
            onInitialize: function () {
                var selectize = this;
                selectize.addOption({id_localidad: -1, localidad: 'Todos'});
                callSetTime(selectize, -1);
            },
			onChange: function(value) {
				console.log("valll");
				console.log(value);
				var selectize = this;
				if (value == null) {
					console.log("vacio");
					selectize.addOption({id_localidad: -1, localidad: 'Todos'});
				}else if (value.indexOf(-1) != -1 && value.length == 1) {
					console.log("encontrado");
					/*$('#select-submarca').prop("disabled", false);
					selectSubmarcas(y);*/
				}else if (value.length > 1) {
					console.log("tiene 2");
					selectize.removeItem(-1);
					/*var selectize = this;
	                selectize.addOption({id_localidad: "burzum", localidad: 'Todosss'});*/
				}
          	},
        });
    };

    var split = function( val ) {
		return val.split( /,\s*/ );
	}
	var extractLast = function( term ) {
		return split( term ).pop();
	}

	var resetSelect = function(x) {
        var sf = "";
        $.each(x, function(i) {
            var sel = $(this);
            if(sel[0].selectize) {
                sel[0].selectize.destroy();
            }
            if (i == 0) sf = sel;
        });
        return sf;
    }

    var callSetTime = function(sel, val) {
        setTimeout(function(){
            if (typeof val !== 'undefined') {
                sel.setValue(val);
            }else {
                //sel.setValue();
            }
        },100);
    }

    var changeAnio = function() {
    	select_tema.val(1).trigger('change');
    }

    var selectTema = function(x) {
    	select_tema.html("");
	    var i = 0, len = all_temas.length;
	    while (i < len) {
	       	select_tema.append(new Option(all_temas[i].tema, all_temas[i].cve_tem));
	        i++
	    }
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
        /*$.each(all_subtemas, function(i, v) {

         	if (v.subtema != null) {
	         	var sub_anio = v.subtema.split(' ');
				var res_anio = sub_anio[sub_anio.length-1];
				/*console.log("res_aniozzzzzzzzz");
				console.log(res_anio);*/

		        //if (v.cve_tem == x && anio_selected == res_anio) {
		        /*if (v.cve_tem == x) {
		        	select_subtema.append(new Option(v.subtema, v.cve_sub));
		        }
		    }
	    });*/
        var i = 0, len = all_subtemas.length;
	    while (i < len) {
	       	if (all_subtemas[i].subtema != null) {
	         	/*var sub_anio = all_subtemas[i].subtema.split(' ');
				var res_anio = sub_anio[sub_anio.length-1];*/
				/*console.log("res_aniozzzzzzzzz");
				console.log(res_anio);*/

		        if (all_subtemas[i].cve_tem == x && anio_selected == all_subtemas[i].anio) {
		        //if (all_subtemas[i].cve_tem == x) {
		        	//select_subtema.append(new Option(all_subtemas[i].subtema, all_subtemas[i].cve_sub));
		        	select_subtema.val(all_subtemas[i].subtema);
		        	select_subtema_id.val(all_subtemas[i].cve_sub);
		        	indicadores(all_subtemas[i].cve_sub);
		        	check_all.prop('checked', false);
    				$("#indicadores-0").prop('checked', true);
		        }
		    }


	        i++
	    }
    }

    var changeSubtema = function() {
    	var value = $(this).val();
    	if (value != "") {
    		indicadores(value);
    	}else {
    		//select_subtema.prop("disabled", true);
    	}
    	check_all.prop('checked', false);
    	$("#indicadores-0").prop('checked', true);
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
					var che = "";
					if (i == 0)	che = "checked"; 
			        check_indicadores.append('<div><input type="checkbox" class="indicadores-check" name="indicadores-' + i + '" id="indicadores-' + i + '" value="' + v.value + '" ' + che + '> ' + v.label + '</div>')
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
		if (!one_true) {
			check_all.prop('checked', false);
			$("#indicadores-0").prop('checked', true);
		}
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

			for (var i = all_indicadores.length - 1; i >= 0; i--) {
				nom_ind[all_indicadores[i].cve_ind] = all_indicadores[i].indicadores;
			}
        	
        	$(".load-data").hide(300, function() {
				$(".content-filters").show(600, function() {
	      			/*initMod.apiCall(apiDataLocalidades).then(function(res){
	      				console.log("res loccc");
    					console.log(res);
	      				all_localidades = res.localidades;
	      				//selectLocalidad();
	      			}, function(reason, json){
						console.log("non");
					 	initMod.debugThemes(reason, json);
					});*/
				});
			});
			/*initMod.apiCall(apiDataLateFormat).then(function(res){
				console.log("res alter 222");
				console.log(res);
				all_municipios_format = res.municipios_format;
	        	all_estados_format = res.estados_format;
			}, function(reason, json){
				console.log("non");
			 	initMod.debugThemes(reason, json);
			});*/
        }, function(reason, json){
			console.log("non");
		 	initMod.debugThemes(reason, json);
		});
	}

	var getEstadoFormat = function(x) {
		return all_estados_format[x];
	}

	var getMunicipioFormat = function(x) {
		return all_municipios_format[x];
	}

	var l;

	var buscarRes = function() {
		/*btn_excel.hide();
		btn_pdf.hide();*/
		btn_export.hide();
		l = $(this).ladda();
		l.ladda( 'start' );
		var sList = [];
		$('#check-indicadores input').each(function () {
		    if (this.checked) {
		    	sList.push('"' + $(this).val() + '"');
		    }
		});
		var in_end= sList.join(",");

		apiDataAllFilter.methods['all_filter_' + this_module]['data'] = {
			//id_localidad: $("#select-localidad-id").val(),
			anio: $("#anio").val(),
			indicadores: in_end,
			debug: $("#debug").val(),
			localidades: $("#select-localidad").val()
		}
		getInitResponse();//
	}

	var generateExcel = function() {

		console.log("apiDataExcel");
		console.log(apiDataExcel);

		var sList = [];
		$('#check-indicadores input').each(function () {
		    if (this.checked) {
		    	sList.push('"' + $(this).val() + '"');
		    }
		});
		var in_end= sList.join(",");

		apiDataExcel.methods['export-excel']['data'] = {id_localidad: $("#select-localidad-id").val(), anio: $("#anio").val(), indicadores: in_end, debug: $("#debug").val()}

		
		
		initMod.apiCall(apiDataExcel).then(function(res, status, xhr){
			console.log("res de nuestro nuevo modulo" + module_upper);
			console.log(res);

			window.open('temp-excel/' + res.file_name, '_blank');

		}, function(reason, json){
			console.log("non hgdf");
			l.ladda( 'stop' );
			if ($("#debug").val() == 'debug') {
				$(".res-error-2").html("Error-2 msg: " + reason.responseText).show(1000);
			}else {
				$(".res-error-2").html("Error en la consulta").show(1000);
			}
		 	initMod.debugThemes(reason, json);


		});
	}

	var generatePdf = function() {

		var sList = [];
		$('#check-indicadores input').each(function () {
		    if (this.checked) {
		    	sList.push('"' + $(this).val() + '"');
		    }
		});
		var in_end= sList.join(",");

		apiDataPdf.methods['export-pdf']['data'] = {id_localidad: $("#select-localidad-id").val(), anio: $("#anio").val(), indicadores: in_end, debug: $("#debug").val()}

		
		
		initMod.apiCall(apiDataPdf).then(function(res, status, xhr){
			console.log("res de nuestro nuevo modulo" + module_upper);
			console.log(res);

			window.open('temp-pdf/' + res.file_name, '_blank');

		}, function(reason, json){
			console.log("non hgdf");
			l.ladda( 'stop' );
			if ($("#debug").val() == 'debug') {
				$(".res-error-2").html("Error-2 msg: " + reason.responseText).show(1000);
			}else {
				$(".res-error-2").html("Error en la consulta").show(1000);
			}
		 	initMod.debugThemes(reason, json);


		});
	}

	var generateExport = function() {
		var sList = [];
		$('#check-indicadores input').each(function () {
		    if (this.checked) {
		    	sList.push('"' + $(this).val() + '"');
		    }
		});
		var in_end= sList.join(",");
		apiDataExport.methods['export']['data'] = {
			id_localidad: $("#select-localidad-id").val(),
			anio: $("#anio").val(),
			indicadores: in_end,
			debug: $("#debug").val(),
			localidades: $("#select-localidad").val()
		}
		var l = $(this).ladda();
		l.ladda( 'start' );
		initMod.apiCall(apiDataExport).then(function(res, status, xhr){
			console.log("res de nuestro nuevo modulo" + module_upper);
			console.log(res);
			//return;

			window.open('temp-excel/' + res.excel.file_name, '_blank');
			window.open('temp-pdf/' + res.pdf.file_name, '_blank');
			l.ladda( 'stop' )

		}, function(reason, json){
			console.log("non hgdf");
			l.ladda( 'stop' );
			if ($("#debug").val() == 'debug') {
				$(".res-error-2").html("Error-2 msg: " + reason.responseText).show(1000);
			}else {
				$(".res-error-2").html("Error en la consulta").show(1000);
			}
		 	initMod.debugThemes(reason, json);


		});
	}

	var bindFilters = function() {
        $("#btn-buscar").on("click", buscarRes);
        select_estado.on("change", changeEstado);
        select_anio.on("change", changeAnio);
        select_tema.on("change", changeTema);
        select_subtema.on("change", changeSubtema);
        check_all.on("click", checkAllIndicadores);
        $(document).on('click','.indicadores-check', checkVisible);
        /*btn_excel.on('click', generateExcel);
        btn_pdf.on('click', generatePdf);*/
        btn_export.on('click', generateExport);
    };

	var init = function () {
		console.log("opeth");
        initAlterData();
        bindFilters();
    };

	return {
	    init : init,
	    this_module: this_module,
	    getEstadoFormat: getEstadoFormat,
	    getMunicipioFormat: getMunicipioFormat
	}
})();