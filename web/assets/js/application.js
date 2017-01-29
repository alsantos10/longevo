$.fn.chamado = function () {
    var me = $(this);

    $('[name=numero]', me).on('blur', function () {
        var num_pedido = $(this).val();

        $.ajax({
            url: '/pedidos/buscar',
            method: "POST",
            data: { id_pedido: num_pedido },
            
            statusCode: {
                404: function () {
                    console.log("page not found");
                }
            }
        }).done(function(data){
            if(data.data){
                me.bindFields(data.data, ['titulo']);
            } else {
                alert("Número de Pedido não foi encontrado");
                $(this).focus();
                return false;
            }
        }).fail(function(data){
            console.log("error", data);
        });
    });
    
    $('[name=email]', me).on('blur', function () {
        var email = $(this).val();

        $.ajax({
            url: '/clientes/buscar',
            method: "POST",
            data: { email: email },
            
            statusCode: {
                404: function () {
                    console.log("page not found");
                }
            }
        }).done(function(data){
            console.log("Done:", data);
            if(data.data){
                me.bindFields(data.data, ['email','nome']);
            }
        }).fail(function(data){
            console.log("error", data);
        });
    });

    me.bindFields = function(data, fields){
        for (var i=0; i<fields.length; i++){
            $('[name='+fields[i]+']', me).val(data[fields[i]]);
        }
    };

};

$('#formchamado').chamado();