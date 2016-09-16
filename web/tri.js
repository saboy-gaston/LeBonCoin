$('document').ready(function () {
    
    $('#projets').html("{%if result.Categorie.type == %}");
    $('#oke').change(function () {
      

        $.ajax({
            type: 'POST',
            url: "http://localhost/LebonCoin/web/app_dev.php/annonces/ajax",
            datatype: "text",
            data:"&select=" + "{%if result.Categorie.type ==" + $('#oke').val() + "%}",
            success: function (data) {
                
                $('#projets').html(data).show();
            }
        });

    });


});



