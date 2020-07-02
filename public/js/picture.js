var $commentsForm;
var $pictureForm;
var $commentsContainer;
var $editImageButton;
var $deleteImageButton;
var $buttonYes;
var $buttonNo;
var $pictureTitle;
var $pictureImage;

var _addComment = function(author, comment)
{
    var id = Math.floor(Math.random() * 35) + 1;
    var newComment = '<article id="' + id +'" class="picture-comment">' +
        '<div class="d-flex flex-row justify-content-between w-95 comment-heading">' +
        '<h5>'+ author +'</h5>' +
        '<button type="button" class="btn btn-danger btn-xs comment-delete" onclick="_deleteComment(\'' + id + '\')">x</button>' +
        '</div>' +
        '<span class="picture-comment">' +
        comment +
        '</span>' +
    '</article>'

    $commentsContainer.prepend(newComment);
};

var _deleteComment = function(commentId) {
    $('article#' + commentId).remove();
}

var _maxComments = function()
{
    return $('article.picture-comment').length >= 10;

}

var _editPicture = function(title,imageSrc)
{
    $pictureTitle.text(title);
    $pictureImage.attr('src',imageSrc);
};

var deletePicture = function()
{
    _startLoading();
    var picId = $('#main-picture').attr('picture-id');
    console.log(picId);
}

var closeDialogue = function()
{
    $buttonYes.unbind('click');
    $buttonNo.unbind('click');
    _hideLvl1();
}

$(function(){
    $commentsForm = $('#comments-form');
    $commentsContainer = $('#comments-container');
    $editImageButton = $('#edit-image-button');
    $deleteImageButton = $('#delete-image-button');
    $buttonYes = $('a.confirmation-dialogue-yes');
    $buttonNo = $('a.confirmation-dialogue-no');
    $pictureForm = $('#picture-form');
    $pictureTitle = $('#picture-display-title');
    $pictureImage = $('#main-picture');

    if (_maxComments()) {
        _disableForm($commentsForm);
    }

    $commentsForm.submit(function(e) {
        e.preventDefault();
        _startLoading();
        if (_maxComments()) {
            _disableForm($commentsForm);
        } else {
            _addComment('new', $(this).find('#comment-body').val());
        }
        $(this).find('#comment-body').val('');

        _stopLoading();
    });

    $deleteImageButton.click(function(){
        $('#confirmation-message').text('Are you sure you want to delete this image?');
        _showConfirmation();
        $buttonYes.bind('click', deletePicture);
        $buttonNo.bind('click', closeDialogue);
    });

    $editImageButton.click(function(){
        $('#picture-title').val($pictureTitle.text())
        $blacklvl1.show();
        $('#picture-edit-modal').show();
    });

    $pictureForm.submit(function(e){
        e.preventDefault();
        _startLoading();
        _editPicture(
            $('#picture-title').val(),
            $pictureImage.attr('src') //#TODO dynamic
        );
        _stopLoading();
        _hideLvl1();
    });


});