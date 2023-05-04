'use strict';

function handleFileSelect(evt)
{
    var file = evt.target.files;
    var f = file[0]
    if (!f.type.match('image.*')) {
        $("#editAdminDataFormError").modal('show');
    }
    var reader = new FileReader();
    reader.onload = (function(theFile) {
        return function(e) {
            var span = document.createElement('span');
            span.innerHTML = ['<img style="height: 80px; border: 1px solid #000; margin: 10px 5px 0 0;" src="', e.target.result,
                            '" title="', escape(theFile.name), '"/>'].join('');
            document.getElementById('previewPicture').innerHTML = "";
            document.getElementById('previewPicture').insertBefore(span, null);
        };
      })(f);
    reader.readAsDataURL(f);
}

//Редирект на
function redirectTo(url, time = 5)
{
    setTimeout(function(){
        window.location.href = url;
    }, time * 1000);
}

function insertAtCaret(areaId, text)
{
    var txtarea = document.getElementById(areaId);
        if (!txtarea) { return; }

        var scrollPos = txtarea.scrollTop;
        var strPos = 0;
        var br = ((txtarea.selectionStart || txtarea.selectionStart == '0') ?
            "ff" : (document.selection ? "ie" : false ) );
        if (br == "ie") {
            txtarea.focus();
            var range = document.selection.createRange();
            range.moveStart ('character', -txtarea.value.length);
            strPos = range.text.length;
        } else if (br == "ff") {
            strPos = txtarea.selectionStart;
        }

        var front = (txtarea.value).substring(0, strPos);
        var back = (txtarea.value).substring(strPos, txtarea.value.length);
        txtarea.value = front + text + back;
        strPos = strPos + text.length;
        if (br == "ie") {
            txtarea.focus();
            var ieRange = document.selection.createRange();
            ieRange.moveStart ('character', -txtarea.value.length);
            ieRange.moveStart ('character', strPos);
            ieRange.moveEnd ('character', 0);
            ieRange.select();
        } else if (br == "ff") {
            txtarea.selectionStart = strPos;
            txtarea.selectionEnd = strPos;
            txtarea.focus();
        }
    txtarea.scrollTop = scrollPos;
}

//удаление страницы
function deletePage(id)
{
    $.ajax({
        type: 'POST',
        url: '/editpage',
        cache: false,
        dataType: 'json',
        data: {'mode':"DeletePage", 'id':id},
        success: function(data){
                    if (data == true) {
                        setTimeout( 'location="/admin/pagelist";', 5 );
                    } else {
                        $("#deleteWindowError").modal('show');
                    }
                }
    });
}
//удаление файла
function deleteFile(fileName)
{
    $.ajax({
        type: 'POST',
        url: '/admin/files',
        cache: false,
        dataType: 'json',
        data: {'mode':"DeleteFile", 'fileName':fileName},
        success: function(data){
                    if (data == true) {
                        setTimeout( 'location="/admin/files";', 5 );
                    } else {
                        $("#deleteWindowError").modal('show');
                    }
                }
    });
}
//показать окно выбора файла
function showAddFileForm()
{
    $("#addMediaForm").modal('show');
}
//показать окно выбора файла для тизерпика
function showTeaserpicForm()
{
    $("#addTeaserForm").modal('show');
}
//вставить имя файла
function selectFile(fileName)
{
    insertAtCaret('contentTextarea', `<img src="/files/${fileName}" class="img-fluid">`);
    $("#addMediaForm").modal('hide');
}
//вставить имя файла кдпв
function selectTeaserpic(fileName)
{
    document.getElementById(`teaserpicInput`).value = fileName;
    $("#addTeaserForm").modal('hide');
}

//удаление подписки
function deleteSub(id)
{
    $.ajax({
        type: 'POST',
        url: '/admin/subscribtions',
        cache: false,
        dataType: 'json',
        data: {'mode':"DeleteSub", 'id':id},
        success: function(data){
                    if (data == true) {
                        setTimeout( 'location="/admin/subscribtions";', 5 );
                    } else {
                        $("#deleteWindowError").modal('show');
                    }
                }
    });
}
//удаление комментария
function deleteComment(id)
{
    $.ajax({
        type: 'POST',
        url: '/admin/comments',
        cache: false,
        dataType: 'json',
        data: {'mode':"DeleteComment", 'id':id},
        success: function(data){
                    if (data == true) {
                        setTimeout( 'location="/admin/comments/1";', 5 );
                    } else {
                        $("#deleteWindowError").modal('show');
                    }
                }
    });
}
//модерирование комментария
function moderateComment(id)
{
    $.ajax({
        type: 'POST',
        url: '/admin/comments',
        cache: false,
        dataType: 'json',
        data: {'mode':"ModerateComment", 'id':id},
        success: function(data){
                    if (data[0] == true) {
                        document.getElementById(`moderatedStateField${id}`).innerHTML = data[1];
                    } else {
                        $("#deleteWindowError").modal('show');
                    }
                }
    });
}
//удаление ссылки
function deleteLink(id)
{
    $.ajax({
        type: 'POST',
        url: '/editlink',
        cache: false,
        dataType: 'json',
        data: {'mode':"DeleteLink", 'id':id},
        success: function(data){
                    if (data == true) {
                        setTimeout( 'location="/admin/linklist";', 5 );
                    } else {
                        $("#deleteLinkWindowError").modal('show');
                    }
                }
    });
}
//удаление рубрики
function deleteCat(id)
{
    $.ajax({
        type: 'POST',
        url: '/admin/editcat',
        cache: false,
        dataType: 'json',
        data: {'mode':"deleteCat", 'id':id},
        success: function(data){
                    if (data == true) {
                        setTimeout( 'location="/admin/catlist";', 5 );
                    } else {
                        $("#deleteCatWindowError").modal('show');
                    }
                }
    });
}
//удаление статьи
function deletePost(postid)
{
    $.ajax({
        type: 'POST',
        url: '/editpost',
        cache: false,
        dataType: 'json',
        data: {'mode':"deletePost", 'postid':postid},
        success: function(data){
                    if (data == true) {
                        setTimeout( 'location="/admin/postlist/1";', 5 );
                    }
                }
    });
}

//переместить форму комментария к комменту, на который производится ответ
function moveForm(block, id)
{
    $(block).append( $('#commentForm') );
    $('#parentIdForm').val(id);
}
//если документ загрузился - поехали
$(document).ready(function(){

//валидация формы
$.fn.checkRequired = function() {
    var res=true;
    $(this).find(":input[required]").each(function(){
      var that=this;
      if ($(this).val()=="") {
        $(this).after('<span class="error">This field is required</span>');
        res=false;
      }
      setTimeout(function(){$(".error").remove();},700);
    });
    return res;
}

//отправка формы с диалогом
$("#editButton").click(function(e){
        if ($("#editDataForm").checkRequired()) {
        e.preventDefault();
        var $button=$(this);
        if (window.location.pathname.split('/')[1] == 'admin') {
            var url = "/admin/" + window.location.pathname.split('/')[2];
        } else {
            var url = "/" + window.location.pathname.split('/')[1];
        }
        $button.attr("disable",true);
        var $that = $('#editDataForm'),
        formData = new FormData($that.get(0));
        $.ajax({
            url: url,
            type: "POST",
            contentType: false,
            processData: false,
            data: formData,
            dataType: 'json',
            success: function(data){
                document.getElementById('statusMessage').innerHTML = data[1];
                $("#registerFormWindowMessage").modal('show');
                if (typeof data[2] !== 'undefined') {
                    window[data[2]](data[3],data[4]);
                }
            },
            error: function(data){
                alert('ajax error');
            }
        });
        }
    return false;
});

//добавление и редактирование универсальная форма без диалога
$("#editAdminButton").click(function(e){
        if ($("#editDataAdminForm").checkRequired()) {
        e.preventDefault();
        var $button=$(this);
        if (window.location.pathname.split('/')[1] == 'admin') {
            var url = "/admin/" + window.location.pathname.split('/')[2];
        } else {
            var url = "/" + window.location.pathname.split('/')[1];
        }
        $button.attr("disable",true);
        var $that = $('#editDataAdminForm'),
        formData = new FormData($that.get(0));
        $.ajax({
            url: url,
            type: "POST",
            contentType: false,
            processData: false,
            data: formData,
            dataType: 'json',
            success: function(data){
                if (data == true) {
                    $("#editAdminDataFormSuccess").modal('show');
                } else {
                    $("#editAdminDataFormError").modal('show');
                }
            },
            error: function(data){
                alert('ajax error');
            }
        });

        }
    return false;
});

//проверка формата картинки
$('#addImages').on('change', function () {
         var files = this.files;

         for (var i = 0; i < files.length; i++) {
             var file = files[i];

             if ( !file.type.match(/image\/(jpeg|jpg|png|gif)/) ) {
                 alert( 'Фотография должна быть в формате jpg, png или gif' );
                 continue;
             }
             if ( file.size > maxFileSize ) {
                 alert( 'Размер фотографии не должен превышать 2 Мб' );
                 continue;
             }
             preview(files[i]);
         }
    this.value = '';
});

//нужно для превью в механизме загрузки картинок
if (document.getElementById('uploadUserpicControl')) {
    document.getElementById('uploadUserpicControl').addEventListener('change', handleFileSelect, false);
}


//список статей в админке
//$("#adminPostList").click(function(e){
//    postList();
//});


//---------------------end---------
});
