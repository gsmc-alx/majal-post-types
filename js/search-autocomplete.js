jQuery(document).ready(function ($){
    var acs_action = 'majal_autocompletesearch';
    $("#s").autocomplete({
        source: function(req, response){
            $.getJSON(MajalAcSearch.url+'?callback=?&action='+acs_action, req, response);
        },
        select: function(event, ui) {
            window.location.href=ui.item.link;
        },
        minLength: 3,
    });
});