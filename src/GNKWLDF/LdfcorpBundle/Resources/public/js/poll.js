var Vote = function(selector, option, route, timeout)
{
    this.selector = selector;
    this.option = option;
    this.route = route;
    this.timeout = timeout;
}

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
                that.decompte(that.timeout, true);
                setTimeout(function() {
                    form.find('input[type="submit"]').prop('disabled', false);
                    form.find('select[name="'+that.option+'"]').prop('disabled', false);
                }, that.timeout * 1000);
            },
            statusCode: {
                403 : function() {
                    form.find('input[type="submit"]').prop('disabled', true);
                    form.find('select[name="'+that.option+'"]').prop('disabled', true);
                }
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