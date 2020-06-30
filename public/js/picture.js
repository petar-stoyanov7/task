var $form;
var $commentsContainer;


var _addComment = function(author, comment)
{
    var newComment = '<article class="picture-comment">' +
        '<h5>' + author + '</h5>' +
        '<span class="picture-comment">' +
        comment +
        '</span>' +
    '</article>';

    $commentsContainer.prepend(newComment);
};

var _maxComments = function()
{
    return $('article.picture-comment').length >= 10;

}

$(function(){
    $form = $('#comments-form');
    $commentsContainer = $('#comments-container');

    if (_maxComments()) {
        _disableForm($form);
    }

    $form.submit(function(e) {
        e.preventDefault();
        _startLoading();
        if (_maxComments()) {
            _disableForm($form);
        } else {
            _addComment('new', $(this).find('#comment-body').val());
        }
        $(this).find('#comment-body').val('');

        _stopLoading();
    });
});