$(function(){   
    
    //var greensh = window.base;
    //greensh.UPLOAD = greensh.APP+'/'+'News'+'/'+'upload';
    //绑定上传
    var uploader = new plupload.Uploader({
        browse_button : 'thumb',
        url : uuul,
        filters: {
          mime_types : [ //只允许上传图片和zip文件
            { title : "Image files", extensions : "jpg,jpeg,png" }
          ],
          max_file_size : '10000kb'
          //prevent_duplicates : true 
        },
      /* flash_swf_url : greensh.COMMON+'/plupload/js/Moxie.swf', 
        silverlight_xap_url :  greensh.COMMON+'/plupload/js/Moxie.xap'*/
    });
    
    //初始化对象
    uploader.init();

    // 当文件被添加之到上传队列后，触发的事件
    uploader.bind('FilesAdded', function(uploader,file){
        uploader.start();
        

    });
    
    // 文件上传成功之后返回的文件信息
    uploader.bind('FileUploaded', function(uploader,file,responseObject){
        //console.log(responseObject.view);
        
        //return false;
        
        var json_data = $.parseJSON(responseObject.response);
       //alert(json_data.view);
       console.log(json_data);
        if(json_data){
            
            //$("input[name='thumb']").val(json_data.info); 
            //$('#path').html(file.name);
            $("#thumb-img").html('');
            var img = '<input type="hidden" name="pic" class="thumb" value="/'+json_data.view+'"><img src="'+uuur+json_data.view+'" alt="" style="max-height:300px;">';
            $("#thumb-img").html(img);
        }else{
         alert(json_data.info);
        }

    });

    // 文件失败之后，返回错误信息
    uploader.bind('Error', function(uploader,errObject){
        console.log(errObject);
        var errortip;
        if(errObject.message!=''){
            errortip="报错啦!文件格式或大小不符合规定";
        };
        var imgg = '<span  name="thumb" class="thumb help-block" style="color: #F9A646">'+errortip+'</span>';
        $('.thumb-img').empty();
        $('.thumb-img').show();
        $(".thumb-img").html(imgg);
    }); 
    //end

    
});


