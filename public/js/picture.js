var $commentsForm;
var $pictureForm;
var $commentsContainer;
var $editImageButton;
var $deleteImageButton;
var $buttonYes;
var $buttonNo;
var $pictureTitle;
var $pictureImage;

var _addCommentToPage = function(commentId, comment, username)
{
    var newComment = '<article id="' + commentId +'" class="picture-comment">' +
        '<div class="d-flex flex-row justify-content-between w-95 comment-heading">' +
        '<h5>'+ username +'</h5>' +
        '<button type="button" class="btn btn-danger btn-xs comment-delete" onclick="_deleteComment(\'' + commentId + '\')">x</button>' +
        '</div>' +
        '<span class="picture-comment">' +
        comment +
        '</span>' +
    '</article>'

    $commentsContainer.prepend(newComment);
};

var deleteComment = function(commentId)
{
    _startLoading();
    $.ajax({
        type: 'POST',
        url: '/pictures/remove-comment',
        data: {
            'comment_id': commentId
        },
        error: function(error) {
            console.log('error with comment deletion');
            console.log(error);
            _stopLoading();
        },
        success: function(response) {
            response = $.parseJSON(response);
            if (parseInt(response['success']) === 1) {
                _removeComment(commentId);
            }
        }
    }).done(function(){
        _stopLoading();
    });
}

var _removeComment = function(commentId)
{
    $('article#' + commentId).remove();
}

var _maxComments = function()
{
    return $('article.picture-comment').length >= 10;
}

var _editPictureContent = function(title,imageSrc)
{
    $pictureTitle.text(title);
    $pictureImage.attr('src',imageSrc);
};

// var editPicture = function()
// {
//     console.log('function');
//     _startLoading();
//     var data = {
//         'title': $('#picture-title').val()
//     };
//     if ($('#picture-file').val().length) {
//         data['file'] = $('#picture-file').val()
//     }
//     $.ajax({
//         type: 'POST',
//         url: '/pictures/edit-picture',
//         data: data,
//         error: function(err) {
//             console.log('error with picture edit');
//             console.log(err);
//             _stopLoading();
//         },
//         success: function(response) {
//             response = $.parseJSON(response);
//             if (parseInt(response['success']) === 1) {
//                 //
//             }
//         }
//     }).done(_stopLoading);
// }

var addComment = function()
{
    _startLoading();
    $.ajax({
        type: 'POST',
        url: '/pictures/add-comment',
        data: {
            'user_id': $('#comment_user_id').val(),
            'picture_id': $('#comment_picture_id').val(),
            'text': $('#comment_body').val()
        },
        error: function(err) {
            console.log('error with comment');
            console.log(err);
            _stopLoading();
        },
        success: function(response) {
            response = $.parseJSON(response);
            if (parseInt(response['success']) === 1) {
                _addCommentToPage(
                    response['comment_id'],
                    $('#comment_body').val(),
                    response['username']
                );
            }
        }
    }).done(function(){
        _stopLoading();
        $('#comment_body').val('');
    });
}

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
        if (_maxComments()) {
            _disableForm($commentsForm);
        } else {
            addComment();
        }
        $(this).find('#comment-body').val('');
    });

    // $deleteImageButton.click(function(){
    //     $('#confirmation-message').text('Are you sure you want to delete this image?');
    //     _showConfirmation();
    //     $buttonYes.bind('click', deletePicture);
    //     $buttonNo.bind('click', closeDialogue);
    // });

    $editImageButton.click(function(){
        $('#picture_title').val($pictureTitle.text())
        $blacklvl1.show();
        $('#picture-edit-modal').show();
    });


});