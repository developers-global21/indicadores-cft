function filtrar(){
    var enteId=document.getElementById("enteid").value;
    var categoriaId=document.getElementById("categoria").value;
    var periodoId=document.getElementById("periodo").value;
    /*titulo = 'Atención'
    parrafo = "<span class='text-success'>Espere por favor<p  align='center'><img src='../../assets/images/wait2.gif' width='50' height='50'></span>"
    $('#title_modal').html(titulo)
    $('#content_modal').html(parrafo)
    $('#myModal').modal()*/
    $.ajax({
        url: "/carga/busca_indicadores/",
        type: "post",
        dataType: "html",
        data: {
            ente: enteId,
            categoria: categoriaId,
            periodo: periodoId,
        },
    }).done(function (res) {
        var data = JSON.parse(res);
        $('#myModal').modal('hide');
        console.log(data);
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
                cadena+="<input type='text' class='form-control text-right' id='ind_0"+i+"' name='ind_0"+i+"' value='"+data[0][i]['valor']+"' size='10'></td></tr>";
            }
            cadena+="</table></div><div class='row'>&nbsp;</div>";
            cadena+="<div class='row'>";
            cadena+="<div class='col-12 text-right'><input type='' class='btn boton02' value='Guardar' onclick='guardar()'></div>";
            cadena+="</div>";
            document.getElementById("div001").innerHTML=cadena;
            $('#myModal').modal('hide');
        } else {
            titulo = 'Atención'
            parrafo = "<span class='text-danger'>Ocurrió un error que no pudo ser controlado<br>Inténtelo de nuevo</span>"
            $('#title_modal').html(titulo)
            $('#content_modal').html(parrafo)
            $('#myModal').modal()
        }
    });        
}

function guardar(){
    var f = $(this);
    var formData = new FormData(document.getElementById("f1"));
    formData.append("dato", "valor");
    $.ajax({
        url: "/carga/guarda_indicadores/",
        type: "post",
        dataType: "html",
        data: formData,
        cache: false,
        contentType: false,
        processData: false
    }).done(function (res) {
        console.log(res);
        var data = JSON.parse(res); 
        if (data.length > 0) {
            if (data[0]=="1"){
                titulo = 'Atención'
                parrafo = "<span class='text-success'>Registro guardado correctamente</span>"
                $('#title_modal').html(titulo)
                $('#content_modal').html(parrafo)
                $('#myModal').modal()
            }
        } else {
            titulo = 'Atención'
            parrafo = "<span class='text-danger'>Ocurrió un error que no pudo ser controlado<br>Inténtelo de nuevo</span>"
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