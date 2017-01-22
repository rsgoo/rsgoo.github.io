
$(document).ready(function () {
    initUpload();
});
var uploader;
var picCountMax = 9;
function initUpload() {
    uploader = Qiniu.uploader({
        runtimes: 'html5,flash,html4', //上传模式,依次退化
        browse_button: 'pickfiles', //上传选择的点选按钮，**必需**
        uptoken_url: '/post/getUploadToken',
        //Ajax请求upToken的Url，**强烈建议设置**（服务端提供）
        //uptoken : '<Your upload token>',
        //若未指定uptoken_url,则必须指定 uptoken ,uptoken由其他程序生成
        //unique_names: true,
        //默认 false，key为文件名。若开启该选项，SDK会为每个文件自动生成key（文件名）
        save_key: true,
        //默认 false。若在服务端生成uptoken的上传策略中指定了 `sava_key`，则开启，SDK在前端将不对key进行任何处理
        domain: '7teayi.com2.z0.glb.qiniucdn.com',
        //bucket 域名，下载资源时用到，**必需**
        container: 'upload-container', //上传区域DOM ID，默认是browser_button的父元素，
        max_file_size: '20mb', //最大文件体积限制
//        dragdrop: true,
//        file_types:"*.jpg;*.png;*.JPG;*.PNG;*.jpeg;*.JPEG;*.webp;*.WEBP;*.gif;*.GIF",
        filters: {
            mime_types: [
                {title: "Image files", extensions: "jpg,png,jpeg,webp,gif"}
            ]
//            mime_types: "jpg,png,jpeg,webp,gif"
//                prevent_duplicates: true
        },
        flash_swf_url: '/js/libs/plupload/Moxie.swf', //引入flash,相对路径
        max_retries: 3, //上传失败最大重试次数
        chunk_size: '4mb', //分块上传时，每片的体积
        auto_start: true, //选择文件后自动上传，若关闭需要自己绑定事件触发上传
        init: {
            'FilesAdded': function (up, files) {
                if (uploader.files.length > picCountMax) { // 最多上传3张图
                    alert("每次最多选择9张照片");
                    uploader.splice(picCountMax, 999);
                }
                if (uploader.files.length >= picCountMax) {
                    hideUploadButton();
                }
                plupload.each(files, function (file) {
                    previewImage(file, showPic);
                });
            },
            'BeforeUpload': function (up, file) {
                // 每个文件上传前,处理相关的事情
                //重点在这里，上传的时候自定义参数信息 
//                    uploader.setOption("multipart_params", {"apptype":${param.type}, "id":${param.id}, "fileType":map[file.id]});
            },
            'UploadProgress': function (up, file) {
                setUploadProcess(file.id, file.percent);
            },
            'FileUploaded': function (up, file, info) {
                // 每个文件上传成功后,处理相关的事情
                // 其中 info 是文件上传成功后，服务端返回的json，形式如
                // {
                //    "hash": "Fh8xVqod2MQ1mocfI4S4KpRL6D98",
                //    "key": "gogopher.jpg"
                //  }
                // 参考http://developer.qiniu.com/docs/v6/api/overview/up/response/simple-response.html
                // var domain = up.getOption('domain');

                var res = JSON.parse(info);
                uploadFinish(file.id, res.key);
            },
            'Error': function (up, err, errTip) {
                if (err.code === plupload.HTTP_ERROR && err.status === 579) {
                    alert(err.file.name + "上传失败，请重新上传");
                    delPic(err.file.id);
                } else {
                    alert(errTip + err.file.name);
                    delPic(err.file.id);
                }
            },
            'UploadComplete': function () {
                //队列文件处理完毕后,处理相关的事情
            }
        }
    });
}


//plupload中为我们提供了mOxie对象
//有关mOxie的介绍和说明请看：https://github.com/moxiecode/moxie/wiki/API
//如果你不想了解那么多的话，那就照抄本示例的代码来得到预览的图片吧
function previewImage(file, callback) {//file为plupload事件监听函数参数中的file对象,callback为预览图片准备完成的回调函数
    if (!file || !/image\//.test(file.type)) {
        callback("/images/loading-2.gif", file.id);
        return; //确保文件是图片
    }
    if (file.type == 'image/gif') {//gif使用FileReader进行预览,因为mOxie.Image只支持jpg和png
        var fr = new mOxie.FileReader();
        fr.onload = function () {
            callback(fr.result, file.id);
            fr.destroy();
            fr = null;
        };
        fr.readAsDataURL(file.getSource());
    } else {
        var preloader = new mOxie.Image();
        preloader.onload = function () {
            //preloader.downsize(550, 400);//先压缩一下要预览的图片,宽300，高300
            var imgsrc = preloader.type == 'image/jpeg' ? preloader.getAsDataURL('image/jpeg', 80) : preloader.getAsDataURL(); //得到图片src,实质为一个base64编码的数据
            callback && callback(imgsrc, file.id); //callback传入的参数为预览图片的url
            preloader.destroy();
            preloader = null;
        };
        preloader.load(file.getSource());
    }
}

function showPic(imgsrc, fileId) {
    var p = '<li id="' + fileId + '">' +
            ' <span class="pickey hide"></span>' +
            ' <img src="' + imgsrc + '" alt=""/>' +
            ' <div class="upload-progress-bg">' +
            '   <div class="upload-progress">' +
            '     <span class="progress-percent" style = "width: 0%;"></span>' +
            '   </div>' +
            ' </div>' +
            ' <a class="btn-close" href="javascript:void(0)" onclick=delPic("' + fileId + '") ></a>' +
            '</li>';

    $("#upload-images").append(p);
//    if (uploader.files.length > 0) {
//        $("#pickfilesDiv").addClass("mutiple-upload");
//        $("#pickfilesDiv").removeClass("first-upload");
//    }

    uploader.refresh(); //调用uploader的刷新，否则选择文件按钮位置发生变化时，旧的位置还可以点击上传文件
}

function delPic(fileId) {
    $("#" + fileId).remove();
    $("#pickfilesDiv").removeClass("hide");
//        uploader.removeFile(fileId);
    //uploader.removeFile  不好用。
    for (var i in uploader.files) {
        if (uploader.files[i].id === fileId) {
            toremove = i;
        }
    }
    uploader.files.splice(toremove, 1);
//    if (uploader.files.length === 0) {
//        $("#pickfilesDiv").removeClass("mutiple-upload");
//        $("#pickfilesDiv").addClass("first-upload");
//    }

    uploader.refresh();
}

function hideUploadButton() {

    $("#pickfilesDiv").addClass("hide");
    uploader.refresh();
}

function setUploadProcess(fileId, percent) {
    if (percent <= 100) {
        var span = $("#" + fileId).find("span.progress-percent");
        span.css("width", percent + "%");
        span.text(percent + "%");
    }

    if (!$(".submit").hasClass("none")) {
        var queueProgress = uploader.total;
        $(".submit-loading").text("正在完成上传 " + queueProgress.percent + "% 。。。");
    }
}

function uploadFinish(fileId, pickey) {

    var imgSrc = $("#" + fileId).find("img").attr("src");
    if (imgSrc === "/images/loading-2.gif") {
        setRealPic(fileId, pickey);
    }

    $("#" + fileId).find(".upload-progress-bg").fadeOut();
    $("#" + fileId).find("span.pickey").text(pickey);
    if (!$(".submit").hasClass("none")) {
        var queueProgress = uploader.total;
        if (queueProgress.percent >= 100) {
            $("#uploadForm").submit();
        }
    }

}

function publish() {

    if (uploader.files.length === 0) {
        alert("请先选择照片");
        return;
    }

    var queueProgress = uploader.total;
    $(".submit").removeClass("none");

    if (queueProgress.percent >= 100) {
        $("#uploadForm").submit();
    } else {
        //还没有上传完毕
        $(".submit-loading").text("正在完成上传 " + queueProgress.percent + "% 。。。");
    }

    return;
}


$("#uploadForm").submit(function () {
    var ajax_option = {
        url: "/post/publish", //默认是form action
        type: "post",
        dataType: "json",
        beforeSerialize: function () {
            var picKeyArr = [];
            $("#upload-images").children("li").each(function () {
                var key = $(this).find("span.pickey").text();
                if (key !== "") {
                    picKeyArr.push(key);
                }
            });
            var pickeys = picKeyArr.join(",");
            $("#pickeys").val(pickeys);
        },
        //data:{'txt':"JQuery"},//自定义提交的数据
        beforeSubmit: function () {
            if ($("#picKeys").val() === "") {
                alert("请先上传照片");
                return false;
            }
            //if($("#txt1").val()==""){return false;}//如：验证表单数据是否为空
        },
        success: function (resp) {//表单提交成功回调函数
            if (resp.state === 0) {
                $(".submit-loading-img").addClass("none");
                $(".submit-loading").text("发布成功。");
                setTimeout("exit()", 1000);
            } else {
                alert("服务繁忙，请稍后再试！");
            }
        },
        error: function (err) {
            alert("服务繁忙，请稍后再试！");
        }
    };
    $('#uploadForm').ajaxSubmit(ajax_option);
    return false;
});

function exit() {
    window.parent.closeUploadPage();
}


function setRealPic(fileId, pickey) {
    $.ajax({
        url: '/post/getPicUrl',
        type: 'post',
        data: {"key": pickey},
        dataType: 'json',
        success: function (response) {
            if (response.state === 0) {
//                downUrl = data.json.data.url;
//                alert(response.data);
                $("#" + fileId).find("img").attr("src", response.data);
            } else {
                alert("failed");
            }
        },
        error: function (msg) {
//            alert("network has a problem");
        }
    });
}