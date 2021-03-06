function search_registro(enteId) {
    $.ajax({
        url: "/publico/public_busca_datos/",
        type: "POST",
        dataType: "html",
        data: {
            id: enteId,
        },
    }).done(function (res) { // 
        console.log(res);
        document.location = res;
    });    
}

function filtrar(){
    var enteId=document.getElementById("enteid").value;
    var categoriaId=document.getElementById("categoria").value;
    var periodoId=document.getElementById("periodo").value;
    console.log(reporteId);
    $.ajax({
        url: "/publico/public_busca_indicadores/",
        type: "post",
        dataType: "html",
        data: {
            enteId: enteId,
            categoriaId: categoriaId,
            periodoId: periodoId,
        },
    }).done(function (res) {
        var data = JSON.parse(res);
        console.log(data);
        $('#myModal').modal('hide');
        if (data[0].length > 0) {
            var cadena ="<div class='row'>";
            cadena+="<div class='col-12 text-right'><button  class='btn boton02' onClick=window.open('"+data[1]+"')>Descarga <img src='../assets/images/pdf.png' width='36' heigth='auto'></button>";
            cadena+="&nbsp;<button  class='btn boton02' onClick=window.open('"+data[2]+"')>Descarga <img src='../assets/images/excel.png' width='36' heigth='auto'></button></div></div>";            
            cadena+="<div class='row'><table class='table table-striped table-bordered'><thead><tr align='center' class='boton02'><th>Nombre</th><th>Valor</th></tr></thead><tbody>";
            for (i=0; i<data[0].length; i++){
                cadena+="<tr><td>"+data[0][i]['concepto']+"</td><td align='right'>";
                //cadena+="<input type='text' class='form-control text-right' id='ind_0"+i+"' name='ind_0"+i+"' value='"+data[0][i]['valor']+"' size='10'></td></tr>";
                cadena+="<label>"+data[0][i]['valor']+" "+data[0][i]['unidad']+"</label></td></tr>";                
            }
            cadena+="</table></div><div class='row'>&nbsp;</div>";
            cadena+="<div class='row'>";
            cadena+="<div class='col-12 text-right'><button  class='btn boton02' onClick=window.open('"+data[1]+"')>Descarga <img src='../assets/images/pdf.png' width='36' heigth='auto'></button>";
            cadena+="&nbsp;<button  class='btn boton02' onClick=window.open('"+data[2]+"')>Descarga <img src='../assets/images/excel.png' width='36' heigth='auto'></button></div></div>";           

            document.getElementById("div001").innerHTML=cadena;
            $('#myModal').modal('hide');
            console.log(data[1])
        } else {
            titulo = 'Atenci??n'
            parrafo = "<span class='text-danger'>Ocurri?? un error que no pudo ser controlado<br>Int??ntelo de nuevo</span>"
            $('#title_modal').html(titulo)
            $('#content_modal').html(parrafo)
            $('#myModal').modal()
        }
    });        
}

function limpiar(){
    document.getElementById("div001").innerHTML="";
    document.location.reload();        
}
