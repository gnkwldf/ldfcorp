var PageForm = function(){
    this.collectionHolder = $('#gnkwldf_ldfcorpbundle_page_links');
    this.length = this.collectionHolder.children('.form-group').length;
    this.$addLink = $('<p><a class="btn btn-success" href="#" class="add_link">Ajouter un lien</a></p>');
    this.$newLinkDiv = $('<div></div>').append(this.$addLink);
};

PageForm.prototype.init = function(){
    this.collectionHolder.append(this.$newLinkDiv);
    
    var parent = this;
    this.collectionHolder.children('.form-group').each(function() {
        parent.addFormDeleteLink($(this));
    });

    this.$addLink.on('click', function(e) {
        e.preventDefault();

        parent.addLinkForm();
    });
};

PageForm.prototype.addLinkForm = function() {
    var prototype = this.collectionHolder.attr('data-prototype');

    var newForm = prototype;
    newForm = newForm.replace(/__name__label__/g, this.length);
    newForm = newForm.replace(/__name__/g, this.length);
    this.length++;
    var $newLinkForm = $(newForm);
    this.$newLinkDiv.before($newLinkForm);
    this.addFormDeleteLink($newLinkForm);
};

PageForm.prototype.addFormDeleteLink = function($linkFormDiv) {
    var $removeFormA = $('<p><a class="btn btn-danger" href="#">Supprimer ce lien</a></p>');
    $linkFormDiv.append($removeFormA);

    $removeFormA.on('click', function(e) {
        e.preventDefault();
        $linkFormDiv.remove();
    });
};

jQuery(document).ready(function() {
    pageForm = new PageForm();
    pageForm.init();
});