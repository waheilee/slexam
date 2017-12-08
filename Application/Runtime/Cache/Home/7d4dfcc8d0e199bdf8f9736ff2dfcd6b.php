<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title>在线交流</title>
    <meta charset="UTF-8">
    <meta http-equiv="expires" content="0">
    <meta http-equiv="pragma" content="no-cache">
    <meta http-equiv="cache-control" content="no-cache">
    <meta name=”keyword” content=”桑磊法考,司法考试,考试,司法,律师“>
    <!-- 公用的css -->
    <link rel="stylesheet" href="/www/slcms/Public/home/css/public.css"/>
    <!-- 本页面的css -->
    <link rel="stylesheet" href="/www/slcms/Public/home/css/chatroom.css"/>
    <link rel="stylesheet" href="/www/slcms/Public/home/css/jquery.mCustomScrollbar.min.css"/>
    <link rel="stylesheet" href="/www/slcms/Public/home/css/jquery.emoji.css"/>
</head>
<body>
<!--主导航-->
<!--白色的-->
<div id="mainnav">
    <div class="wrap">
        <!--logo-->
        <div class="left">
            <a href="<?php echo U('Index/index');?>"><img src="/www/slcms/Public/home/images/logo/logo-red.png" alt=""/></a>
        </div>
        <!--logo结束-->
        <!--导航菜单-->
        <div class="left">
            <ul>
                <a id="turnToContent" href="<?php if(strtolower($Think.CONTROLLER_NAME) == 'index'){echo '#content' ;}else{echo U('Index/index');} ?>" ><li>桑磊题库</li></a>
                <a href="<?php echo U('Video/index');?>" target="_blank"><li>法考课堂</li></a>
                <a href="#" target="_blank"><li>赛事活动</li></a>
                <a href="<?php echo U('Band/index');?>" target="_blank"><li>题霸打榜</li></a>
                <a href="<?php echo U('Online/index');?>" target="_blank"><li>在线交流</li></a>
                <div class="clearfix"></div>
            </ul>
        </div>
        <!--导航菜单结束-->
        <div class="right">
            <!--用户中心链接-->
            <a href="<?php echo U('usercenter/indexx');?>" class="user" target="_blank">
                <img src="/www/slcms/Public/home/images/nav/user.png" alt=""/>用户中心
            </a>
            <!--用户中心链接结束-->
        </div>
    </div>
</div>
<div class="wrap" style="background: #ebebeb;">
    <div class="left">
        <!--导航-->
        <div id="chatroom-nav">
                <ul>
                    <li class="active" onclick="chatroomNav(this)">
                        <div class="nav-icon"><img src="/www/slcms/Public/home/images/chatroom/nav-znxx.png" alt=""/></div>
                        <div class="nav-name">站内消息<span>(</span><span class="num">3</span><span>)</span></div>
                        <div class="nav-new"><img src="/www/slcms/Public/home/images/chatroom/nav-new.png" alt=""/></div>
                    </li>
                    <li onclick="chatroomNav(this)">
                        <div class="nav-icon"><img src="/www/slcms/Public/home/images/chatroom/nav-wd.png" alt=""/></div>
                        <div class="nav-name">我的问答<span>(</span><span class="num"></span><span>)</span></div>
                        <div class="nav-new"><img src="/www/slcms/Public/home/images/chatroom/nav-new.png" alt=""/></div>
                    </li>

                    <li onclick="chatroomNav(this)">
                        <div class="nav-icon"><img src="/www/slcms/Public/home/images/chatroom/nav-wkt.png" alt=""/></div>
                        <div class="nav-name">打个招呼吧！<span>(</span><span class="num"></span><span>)</span></div>
                        <div class="nav-new"><img src="/www/slcms/Public/home/images/chatroom/nav-new.png" alt=""/></div>
                    </li>

                    <div class="nav-eqcode">
                        <div class="img"><img src="/www/slcms/Public/home/images/answer/ewm.png" alt=""/></div>
                        <p>扫描二维码登录手机端</p>
                    </div>
                </ul>
        </div>
    </div>
    <div class="right">
        <div id="chatroom-content">
            <!--站内消息-->
            <div style="display: block;" class="cc-content-box cc-znxx">
                <div class="cc-earlier">
                    <a href="#">更早记录</a>
                </div>
                <div class="cc-message">
                    <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="s-message">
                        <!--系统头像-->
                        <img class="s-message-img" src="<?php echo ($vo["head"]); ?>" alt=""/>
                        <!--时间-->
                        <p class="s-message-time"><?php echo (date('Y-m-d H:i:s',$vo["sendtime"])); ?></p>
                        <!--内容-->
                        <div class="s-message-content">
                            <p class="s-message-name"><?php echo ($vo["username"]); ?></p>
                            <p class="s-message-text text"><?php echo ($vo["content"]); ?></p>
                        </div>
                        <div class="clearfix"></div>
                    </div><?php endforeach; endif; else: echo "" ;endif; ?>
                </div>
                <div class="cc-notmore">
                    没有更多了
                </div>
            </div>
            <!--我的问题-->
            <div style="display: none;" class="cc-content-box cc-znxx">
                <!--更早记录-->
                <div class="cc-earlier">
                    <a href="#">更早记录</a>
                </div>
                <!--对话-->
                <div id="cc-message-submit" class="cc-message">
                    <!--左边-->
                    <div class="ask-message">
                        <!--系统头像-->
                        <img class="ask-message-img" src="/www/slcms/Public/home/images/chatroom/systemimg.png" alt=""/>
                        <!--内容-->
                        <div class="ask-message-content">
                            <!--名字-->
                            <p class="ask-message-xm">张老师</p>
                            <!--时间-->
                            <p class="ask-message-time">2017-10-31  11：20</p>
                            <p class="ask-message-name">针对：国际经济法中提到的那些知识点有些特别偏，有没有针对性的讲解？</p>
                            <p class="ask-message-text">答：在预定留言中提出你的要求，届时我会安排课程通知你</p>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <!--右边 自己方-->
                    <div class="ask-message oneself-message">
                        <!--系统头像-->
                        <img class="ask-message-img" src="/www/slcms/Public/home/images/chatroom/tx/1.png" alt=""/>
                        <!--内容-->
                        <div class="ask-message-content">
                            <!--时间-->
                            <p class="ask-message-time">2017-10-31  11：20</p>
                            <p class="ask-message-text">问：张老师什么时候能将国际经济法中的难点给我们弄几个例子呢？</p>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <!--左边-->
                    <div class="ask-message">
                        <!--系统头像-->
                        <img class="ask-message-img" src="/www/slcms/Public/home/images/chatroom/systemimg.png" alt=""/>
                        <!--内容-->
                        <div class="ask-message-content">
                            <!--名字-->
                            <p class="ask-message-xm">张老师</p>
                            <!--时间-->
                            <p class="ask-message-time">2017-10-31  11：20</p>
                            <p class="ask-message-name">针对：国际经济法中提到的那些知识点有些特别偏，有没有针对性的讲解？</p>
                            <p class="ask-message-text">答：在预定留言中提出你的要求，届时我会安排课程通知你。会尽早给你安排。</p>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <!--右边 自己方-->
                    <div class="ask-message oneself-message">
                        <!--系统头像-->
                        <img class="ask-message-img" src="/www/slcms/Public/home/images/chatroom/tx/1.png" alt=""/>
                        <!--内容-->
                        <div class="ask-message-content">
                            <!--时间-->
                            <p class="ask-message-time">2017-10-31  11：20</p>
                            <p class="ask-message-text">问：张老师什么时候能将国际经济法中的难点给我们弄几个例子呢？难点给我们弄几个例子呢？难点给我们弄几个？</p>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
                <!--已没有更多-->
                <div class="cc-notmore">
                    在线提交留言
                </div>
                <!--提交留言-->
                <div class="submit-message">
                    <!--选择的问题在这个隐藏域-->
                    <input type="hidden" id="submit-message-answer"/>
                    <div class="inputbox">
                        <span>选择问题：</span>
                        <select id="courses" name="courses" class="col-xs-10 col-sm-5" onchange="zdywt(this)">
                            <option value="">--请选择--</option>
                            <?php if(is_array($course)): $i = 0; $__LIST__ = $course;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["id"]); ?>"><?php echo ($vo["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                        </select>
                        <div class="clearfix"></div>
                    </div>
                    <div style="display: none" class="inputbox input">
                        <input type="text" placeholder="请自定义您的问题" onblur="giveanswer(this)"/>
                        <div class="clearfix"></div>
                    </div>
                    <div class="inputbox">
                        <span>附加留言：</span>
                        <textarea name="" id="submit-message-text"></textarea>
                        <div id="editor" style="width: 50px;position: absolute;bottom: 15px;left: 85px;"></div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="inputbox">
                        <button type="button" onclick="addmessage()">确认提交问题</button>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
            <!--打个.招呼-->
            <div style="display: none;" class="cc-content-box cc-zgzh">
                <p class="cc-zgzh-title">
                    亲爱的<span>大法官123</span>，以下是往期题霸霸主提名，打个招呼吧!
                </p>
                <div class="cc-zgzh-chose">
                    <select name="province" id="province">
                        <option value="" >请选择</option>
                        <?php if(is_array($province)): $i = 0; $__LIST__ = $province;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option  value="<?php echo ($vo["area_id"]); ?>" <?php if($vo["area_id"] == $user['province']): ?>selected="selected"<?php endif; ?>><?php echo ($vo["area_name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                    </select>
                    <select name="city" id="city">
                        <option value="">选择地区</option>
                    </select>

                    <button type="submit" id="week">近一周</button>
                    <button type="submit" id="quarter">本季度</button>
                    <button type="submit" id="synthesize">评估级</button>
                </div>
                <div class="cc-user" id="user">

                </div>
                <!--<div class="cc-message">-->
                    <!--<div class="s-message">-->
                        <!--&lt;!&ndash;系统头像&ndash;&gt;-->
                        <!--<img class="s-message-img" src="/www/slcms/Public/home/images/chatroom/systemimg.png" alt=""/>-->
                        <!--&lt;!&ndash;时间&ndash;&gt;-->
                        <!--<p class="s-message-time">2017-10-31  11：20</p>-->
                        <!--&lt;!&ndash;内容&ndash;&gt;-->
                        <!--<div class="s-message-content">-->
                            <!--<p class="s-message-name">最近招呼回应  来自用户 <span>小辣椒叮当</span>：</p>-->
                            <!--<p class="s-message-text">你上周的打榜很厉害呀！以后互相学习，我也是北京地区的，觉得张老师讲的特别好，回头加好友，我推荐给你一些课程。</p>-->
                        <!--</div>-->
                        <!--<div class="clearfix"></div>-->
                    <!--</div>-->
                <!--</div>-->
                <!--<div class="cc-notmore">-->
                    <!--没有更多了-->
                <!--</div>-->
            </div>
            <!--打个招呼的弹出层-->
            <div id="hellomodel">
                <div class="h-box">
                    <div class="h-title">
                        <div class="left">
                            <img src="/www/slcms/Public/home/images/chatroom/dgzh.png" alt=""/>
                            打个招呼
                        </div>
                        <div class="right">
                            <img onclick="closeHello()" src="/www/slcms/Public/home/images/chatroom/chose.png" alt=""/>
                        </div>
                    </div>
                    <div class="h-content">
                        <textarea name="" placeholder="请输入文字信息..."></textarea>
                        <button onclick="helloSubmit(this)">现在交个朋友吧！</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
</div>
<!--底部-->
<div id="footer">
    <div class="wrap">
        <p>
            <a href="#">首页</a>
            <span>丨</span>
            <a href="#">网站声明</a>
            <span>丨</span>
            <a href="#">联系方式</a>
            <span>丨</span>
            <a href="#">加入我们</a>
        </p>
        <p>
            Copyright&nbsp;&copy;&nbsp;2017-&nbsp;桑磊法考(sangleifk.com)&nbsp;京批字第直123456号&nbsp;京ICP备12345678号-1
        </p>
        <p>
            联系电话：&nbsp;&nbsp;&nbsp;4000-000-000&nbsp;&nbsp;&nbsp;&nbsp;010-12345678
        </p>
    </div>
</div>
<script src="/www/slcms/Public/home/js/jquery-1.11.3.js"></script>
<script src="/www/slcms/Public/home/js/jquery.mousewheel-3.0.6.min.js"></script>
<script src="/www/slcms/Public/home/js/jquery.mCustomScrollbar.min.js"></script>
<script src="/www/slcms/Public/home/js/jquery.emoji.js"></script>
<!--省市级联动-->
<script type="text/javascript">
    function loadRegion(obj,id,type,check){

        $.ajax({
            url:"<?php echo U('getRegion');?>",
            type:'post',
            data:{'id':id,'type':type,'check':check},
            success:function(data){
                $(obj).html(data);
            }

        })

    }
    //用于查询打榜用户
    function postRegion(obj,id){
        $.ajax({
            url:"<?php echo U('postRegion');?>",
            type:'post',
            data:{'id':id},
            success:function(data){
                $('#user').html('');//清空div里的元素
                $.each(data,function(i,item){
                    $('#user').append(
                        "<dl id='"+ item.id+"'>"+
                        "<dd><img src='"+item.head+"' /></dd>"+

                        "<dt data='"+item.id+"'>"+item.username+"</dt>"+
                        "</dl>"
                    )
                });
                $('#user').append(
                    "<div class='clearfix'></div>"
                )
            }
        })
    }

    $('#province').change(function(){
        var val = $('#province option:selected').val();
        loadRegion('#city',val);
        postRegion('#city',val)//用于查询打榜用户
    })
    $('#city').change(function(){
        var val = $('#city option:selected').val();
//        loadRegion('#town',val);
        postRegion('#town',val)//用于查询打榜用户
    })

</script>
<script type="text/javascript">

    //查询周榜单
    $("#week").click(function(){
//        var cont = $("form").serialize();
        $.ajax({
            url:"<?php echo U('Online/week',array('s'=>1));?>",
            type:'post',
            dataType:'json',

            success:function(data){
                $('#user').html('');//清空div里的元素
                $.each(data,function(i,item){
                    $('#user').append(
                        "<dl id='"+ item.id+"'>"+
                        "<dd><img src='"+item.head+"' /></dd>"+

                        "<dt data='"+item.id+"'>"+item.username+"</dt>"+
                        "</dl>"
                    )
                });
                $('#user').append(
                    "<div class='clearfix'></div>"
                )
            }
        });
    });
    //查询季度榜单
    $("#quarter").click(function(){
//        var cont = $("form").serialize();
        $.ajax({
            url:"<?php echo U('Online/week',array('s'=>2));?>",
            type:'post',
            dataType:'json',

            success:function(data){
                $('#user').html('');//清空div里的元素
                $.each(data,function(i,item){
                    $('#user').append(
                        "<dl id='"+ item.id+"'>"+
                        "<dd><img src='"+item.head+"' /></dd>"+

                        "<dt data='"+item.id+"'>"+item.username+"</dt>"+
                        "</dl>"
                    )
                });
                $('#user').append(
                    "<div class='clearfix'></div>"
                )
            }
        });
    });
    //查询综合榜单
    $("#synthesize").click(function(){
//        var cont = $("form").serialize();
        $.ajax({
            url:"<?php echo U('Online/week',array('s'=>3));?>",
            type:'post',
            dataType:'json',

            success:function(data){
                $('#user').html('');//清空div里的元素
                $.each(data,function(i,item){
                    $('#user').append(
                        "<dl id='"+ item.id+"'>"+
                        "<dd><img src='"+item.head+"' /></dd>"+

                        "<dt data='"+item.id+"'>"+item.username+"</dt>"+
                        "</dl>"
                    )
                });
                $('#user').append(
                    "<div class='clearfix'></div>"
                )
            }
        });
    });
</script>


<!--菜单导航-->
<script>
//    新的消息条数显示
    function newNum(){
        var num = $("#chatroom-nav").find(".num");
        for(var i= 0;i<num.length;i++){
            if($(num[i]).text()==""){
                $(num[i]).siblings("span").hide();
                $(num[i]).parents("li").children(".nav-new").hide();
            }
        }
    };
    newNum();
</script>
<!--表情-->
<script>
    $("#submit-message-text").emoji({
        icons: [{
            name: "QQ表情",
            path: "images/img/qq/",
            maxNum: 91,
            excludeNums: [41, 45, 54],
            file: ".gif",
            placeholder: "#qq_{alias}#"
        }]
    });
    //    转化表情
    function parse(){
        $("body").find(".text").emojiParse({
            icons: [{
                path: "images/img/qq/",
                file: ".gif",
                placeholder: "#qq_{alias}#"
            }]
        });
    }
    parse();
</script>
<!--提交评论-->
<script>
    //    选择的问题给隐藏域
    function giveanswer(t){
        $("#submit-message-answer").val($(t).val());
    };
    //    自定义的问题给隐藏域
    function zdywt(t){
        if($(t).val()=="zdy"){
            $(t).parents(".inputbox").next(".inputbox").show();
        }else{
            $(t).parents(".inputbox").next(".inputbox").hide();
            $("#submit-message-answer").val($(t).val());
        }
    };
    function addmessage(){
//        获取名字
        var answer = $("#submit-message-answer").val();
//        获取头像
        var img = '<img src="" alt=""/>';
//        获取时间
        var times =  new Date().toLocaleString( );//获取日期与时间
//        获取评论内容
        var text = $("#submit-message-text").val();

        var str = '<div class="ask-message oneself-message">' +
                    '<img class="ask-message-img" src="images/chatroom/tx/1.png" alt=""/>' +
                    '<div class="ask-message-content">' +
                        '<p class="ask-message-time">'+times+'</p>' +
                        '<p class="ask-message-text">问：'+answer+'</br>'+text+'</p> ' +
                    '</div>' +
                    '<div class="clearfix"></div>' +
                '</div>'
        $("#cc-message-submit").append(str);
        parse();
    }
</script>
<!--菜单的点击-->
<script>
    function chatroomNav(t){
        var i = $(t).index();
        console.log(i);
        $("#chatroom-content .cc-content-box:eq("+i+")").show().siblings(".cc-content-box").hide();
        $(t).addClass("active").siblings("li").removeClass("active");
    };
</script>
<!--打个招呼的弹出层-->
<script>
//    打开
    $(function(){
        $("#chatroom-content").find(".cc-user").on("click","dl",function(){
            $("#hellomodel").show();
            toUserId = this.id;
        });
    });
//    关闭
    function closeHello(){
        $("#hellomodel").hide();
    };
//    提交
    function helloSubmit(t){
//        打招呼的话
        var text = $(t).prev("textarea").val();
        $.ajax({
            url:"<?php echo U('message');?>",
            type:'post',
            data:{'rid':toUserId,'text':text},
            success:function(data){
                $(obj).html(data);
            }

        })
        $("#hellomodel").hide();
    }
</script>
</body>
</html>