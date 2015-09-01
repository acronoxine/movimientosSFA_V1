// JavaScript Document/*
/*
function valida(form)
{
	if(form.clave.value == "")
	{
		alert("Indique la clave del programa");
		form.clave.focus();
		return false;
	}
	
	if(form.descripcion.value == "")
	{
		alert("Indique el nombre del programa");
		form.decripcion.focus();
		return false;
	}
	
	return true;
}

function solonumeros(form, e)
{
    if(e.keyCode != 0)
      letra = e.keyCode;
    else
      letra = e.which;
    
    if((letra < 48 || letra > 57) && letra != 37 && letra != 38 && letra != 39 && letra != 40 && letra != 8 && letra != 46)
       return false;
    else
       return true;
}


function busca(dato)
{
	parent.lista.document.location.replace('cat_plazas_lista.php?consulta='+dato);
}
function busca2(dato)
{
	parent.lista.document.location.replace('cat_plazas_lista.php?fecha='+dato);
}

function sueldo(dato)
{
	if(dato != "")
	{
		var id = dato.substr(0, dato.indexOf("|", dato));
		dato = dato.substr(dato.indexOf("|", dato) + 1);
		
		var sueldob = dato.substr(0, dato.indexOf("|", dato));
		dato = dato.substr(dato.indexOf("|", dato) + 1);
		
		var nivel = dato.substr(0);

		form1.sueldobase.value = sueldob;
		form1.plaza.value = nivel;
	}
}
function objetoAjax() 
{
  var xmlHttp=null;
  if (window.ActiveXObject) 
    xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
  else 
    if (window.XMLHttpRequest) 
      xmlHttp = new XMLHttpRequest();
  return xmlHttp;
}

function carga(Resultado,ajax)
{
	ajax.onreadystatechange=function() 
	{
		if (ajax.readyState==4) 
		{
			Resultado.innerHTML = ajax.responseText.tratarResponseText();
		}
	}
}
String.prototype.tratarResponseText=function(){
	var pat=/<script[^>]*>([\S\s]*?)<\/script[^>]*>/ig;
	var pat2=/\bsrc=[^>\s]+\b/g;
	var elementos = this.match(pat) || [];
	for(i=0;i<elementos.length;i++) {
		var nuevoScript = document.createElement('script');
		nuevoScript.type = 'text/javascript';
		var tienesrc=elementos[i].match(pat2) || [];
		if(tienesrc.length){
			nuevoScript.src=tienesrc[0].split("'").join('').split('"').join('').split('src=').join('').split(' ').join('');
		}else{
			var elemento = elementos[i].replace(pat,'$1','');
			nuevoScript.text = elemento;
		}
		document.getElementsByTagName('body')[0].appendChild(nuevoScript);
	}
	return this.replace(pat,'');
}
//--------------------------  Carga el sueldo correspondiente a la categoria solo para revisar
function cargasueldo(idcategoria)
{
	Resultado = document.getElementById('ajax_sueldos');
	ajax=objetoAjax();
	ajax.open("POST", "ajax_sueldos.php",false);
	carga(Resultado,ajax);
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.send("idcategoria="+idcategoria);
}
/* Carga los subprogramas a partir de un id de programa*/
/*function programas(id,desc)
{
	Resultado = document.getElementById('ajax_programas');
	ajax=objetoAjax();
	ajax.open("POST", "ajax_programas.php",false);
	carga(Resultado,ajax);
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.send("idprograma="+id+"&desc="+desc);
}
/* Carga los proyectos a partir de un id de subprograma*/
/*function proyecto(id,desc)
{
	Resultado = document.getElementById('ajax_proyecto');
	ajax=objetoAjax();
	ajax.open("POST", "ajax_proyectos.php",false);
	carga(Resultado,ajax);
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ajax.send("idsprograma="+id+"&desc="+desc);
}/*
 function formato(datos,tipoContrato,plaza,fecha,fecha2,estado,asignacion)
 {
            $.fancybox({
            'href': 'imovimientoper.php?estado='+estado+'&fecha='+fecha+'&fecha2='+fecha2+'&plaza='+plaza+'&tipoContrato='+tipoContrato+'&idnominaemp=' + datos+'&asignacion='+asignacion,
            'autoScale': false,
            'transitionIn': 'none',
            'transitionOut': 'none',
            'width': 970,
            'height': 700,
            'modal': true,
            'type': 'iframe'
	});
}*/
