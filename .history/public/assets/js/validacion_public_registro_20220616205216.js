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
        $('#myModal').modal('hide');
        //console.log(data);
        if (data[0].length > 0) {
            var cadena="<div class='row'><table class='table table-striped table-bordered'><thead><tr align='center' class='boton02'><th>Nombre</th><th>Valor</th></tr></thead><tbody>";
            for (i=0; i<data[0].length; i++){
                /*
                i.nombre,
                i.formula,
                i.descripcion,
                i.ente_id,
                i.categoria_id,
                i.id  as indicador_id,    
                ifnull(r.id,0) as registro_id,
                ifnull(r.periodo_id,0) as periodo_id,
                ifnull(r.valor,0) as valor
                */
                /*console.log(i);
                console.log(data[0][i]['nombre']);
                console.log(data[0][i]['formula']);
                console.log(data[0][i]['descripcion']);
                console.log(data[0][i]['ente_id']);
                console.log(data[0][i]['categoria_id']);
                console.log(data[0][i]['indicador_id']);
                console.log(data[0][i]['registro_id']);
                console.log(data[0][i]['periodo_id']);
                console.log(data[0][i]['valor']);
                console.log('----------------------------------');*/
                /*cadena+="<div class='col-12'>";
                cadena+="<div class='input-group'><label class='input-group-text font-weight-bold'>"+data[0][i]['nombre']+":</label>";
                cadena+="<input type='hidden' class='form-control' id='indice_0"+i+"' name='indice_0"+i+"' value='"+data[0][i]['indicador_id']+"-"+data[0][i]['registro_id']+"'>";
                cadena+="<input type='text' class='form-control' id='ind_0"+i+"' name='ind_0"+i+"' value='"+data[0][i]['valor']+"' size='10'>";
                cadena+="</div></div>";*/

                cadena+="<tr><td>"+data[0][i]['nombre']+"</td><td><input type='hidden' class='form-control' id='indice_0"+i+"' name='indice_0"+i+"' value='"+data[0][i]['indicador_id']+"-"+data[0][i]['registro_id']+"'>";
                cadena+="<input type='text' class='form-control text-right' id='ind_0"+i+"' name='ind_0"+i+"' value='"+data[0][i]['valor']+"' size='10' disabled></td></tr>";
            }
            cadena+="</table></div><div class='row'>&nbsp;</div>";
            document.getElementById("div001").innerHTML=cadena;
            $('#myModal').modal('hide');
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
