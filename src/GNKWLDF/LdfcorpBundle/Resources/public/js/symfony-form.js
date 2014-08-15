var SfForm = function(collection, addText, removeText){
    this.collectionHolder = collection;
    this.addText = addText;
    this.length = this.collectionHolder.children('.form-group').length;
    this.$addLink = $('<p>').append($('<a class="btn btn-success">').text(this.addText));
    this.$newLinkDiv = $('<div>').append(this.$addLink);
    this.removeText = removeText;
};

SfForm.prototype.init = function(){
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

SfForm.prototype.addLinkForm = function() {
    var prototype = this.collectionHolder.attr('data-prototype');

    var newForm = prototype;
    newForm = newForm.replace(/__name__label__/g, this.length);
    newForm = newForm.replace(/__name__/g, this.length);
    this.length++;
    var $newLinkForm = $(newForm);
    this.$newLinkDiv.before($newLinkForm);
    this.addFormDeleteLink($newLinkForm);
};

SfForm.prototype.addFormDeleteLink = function($linkFormDiv) {
    var $removeFormA = $('<p>').append($('<a class="btn btn-danger" href="#">').text(this.removeText));
    $linkFormDiv.append($removeFormA);

    $removeFormA.on('click', function(e) {
        e.preventDefault();
        $linkFormDiv.remove();
    });
};