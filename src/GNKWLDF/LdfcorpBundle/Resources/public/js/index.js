$(document).ready(function(){
    $( ".form" ).submit(function( event ) {
        var form = $(this);
        var datastring = form.serialize();
        $.ajax({
            type: 'POST',
            url: Routing.generate('ldfcorp_vote'),
            data: datastring,
            success: function(m){
                form.find('input[type="submit"]').prop('disabled', true);
                form.find('select[name="pokemon"]').prop('disabled', true);
                decompte(5, true);
                setTimeout(function() {
                    form.find('input[type="submit"]').prop('disabled', false);
                form.find('select[name="pokemon"]').prop('disabled', false);
                }, 5000);
            }
        });
        event.preventDefault();
    });
});

function decompte(cpt, badge)
{
    $('.ldfcorp-wait').text(cpt);
    if(badge)
    {
        $('.ldfcorp-wait').addClass('badge');
    }
    if(cpt > 0)
    {
        setTimeout(function() {
            decompte(cpt-1, false);
        }, 1000);
    }
    else
    {
        $('.ldfcorp-wait').text('');
        $('.ldfcorp-wait').removeClass('badge');
    }
}