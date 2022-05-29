function filtrar(){
    var enteId=document.getElementById("ente_id").value;
    var categoriaId=document.getElementById("categoria").value;
    var periodoId=document.getElementById("periodo").value;
    titulo = 'Atención'
    parrafo = "<span class='text-success'>Espere por favor<p  align='center'><img src='../../assets/images/wait2.gif' width='50' height='50'></span>"
    $('#title_modal').html(titulo)
    $('#content_modal').html(parrafo)
    $('#myModal').modal()
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
        var data = JSON.parse(res)
        console.log(data);
        if (data.length > 0) {
            for (i=0; i<data.length; i++){
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
                console.log(data[0][i]['nombre']);
                console.log(data[0][i]['formula']);
                console.log(data[0][i]['descripcion']);
                console.log(data[0][i]['ente_id']);
                console.log(data[0][i]['categoria_id']);
                console.log(data[0][i]['indicador_id']);
                console.log(data[0][i]['registro_id']);
                console.log(data[0][i]['periodo_id']);
                console.log(data[0][i]['valor']);
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
    document.location.reload();        
}