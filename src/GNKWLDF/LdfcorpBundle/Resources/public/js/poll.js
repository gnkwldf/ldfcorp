var Vote = function(selector, option, route)
{
    this.selector = selector;
    this.option = option;
    this.route = route;
}

$(document).ready(function(){
    var pokemonVote = new Vote($( ".ldfcorp-form-poll-pokemon" ), 'pokemon', Routing.generate('ldfcorp_vote'));
    pokemonVote.init();
    var pollId = $( ".ldfcorp-form-poll" ).attr('data-id');
    var pollVote = new Vote($( ".ldfcorp-form-poll" ), 'entry', Routing.generate('ldfcorp_poll_vote', { id : pollId}));
    pollVote.init();
});

Vote.prototype.init = function()
{
    var that = this;
    this.selector.submit(function( event ) {
        var form = $(this);
        var datastring = form.serialize();
        $.ajax({
            type: 'POST',
            url: that.route,
            data: datastring,
            success: function(m){
                form.find('input[type="submit"]').prop('disabled', true);
                form.find('select[name="'+that.option+'"]').prop('disabled', true);
                that.decompte(5, true);
                setTimeout(function() {
                    form.find('input[type="submit"]').prop('disabled', false);
                form.find('select[name="'+that.option+'"]').prop('disabled', false);
                }, 5000);
            }
        });
        event.preventDefault();
    });
}

Vote.prototype.decompte = function(cpt, badge)
{
    var that = this;
    this.selector.find('.ldfcorp-wait').text(cpt);
    if(badge)
    {
        this.selector.find('.ldfcorp-wait').addClass('badge');
    }
    if(cpt > 0)
    {
        setTimeout(function() {
            that.decompte(cpt-1, false);
        }, 1000);
    }
    else
    {
        this.selector.find('.ldfcorp-wait').text('');
        this.selector.find('.ldfcorp-wait').removeClass('badge');
    }
}