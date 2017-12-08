<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title>用户中心-课堂订制</title>
    <meta charset="UTF-8">
    <meta http-equiv="expires" content="0">
    <meta http-equiv="pragma" content="no-cache">
    <meta http-equiv="cache-control" content="no-cache">
    <meta name=”keyword” content=”桑磊法考,司法考试,考试,司法,律师“>
    <!-- 公用的css -->
    <link rel="stylesheet" href="/www/slcms/Public/home/css/user-center-public.css"/>
    
    <!-- 本页面的css -->
    <link rel="stylesheet" href="/www/slcms/Public/home/css/user-center-fxkt.css"/>
</head>
<body>
<div class="wrap">
    <!--侧边栏导航-->
    <div id="sidebar-nav">
        <!--头像 用户名-->
        <div class="top">
            <img src="/www/slcms/Public/home/images/usercenter/logo.png" class="logo" alt=""/>
            <div class="avatar">
                <img src="/www/slcms/Public/home/images/usercenter/tx.png" alt=""/>
            </div>
            <div class="name">大法官123</div>
            <div class="account">账户：[普通账户]</div>
        </div>
        <!--菜单-->
         <div class="list">
            <ul>
                <a href="<?php echo U('usercenter/indexx');?>"><li class="<?php if($Think.ACTION_NAME == 'indexx'){echo 'active' ;} ?>">基本资料</li></a>
                <a href="<?php echo U('Recharge/recharge');?>"><li class="<?php if($Think.ACTION_NAME == 'recharge'){echo 'active' ;} ?>">账户充值</li></a>
                <a href=""><li>桑磊会员</li></a>
                <a href="<?php echo U('userhistory/historicalAnswer');?>"><li class="<?php if($Think.ACTION_NAME == 'historicalAnswer'){echo 'active' ;} ?>">历史答卷</li></a>
                <a href="<?php echo U('userhistory/historyList');?>"><li class="<?php if($Think.ACTION_NAME == 'historyList'){echo 'active' ;} ?>">历史打榜</li></a>
                <a href="<?php echo U('userhistory/assess');?>"><li class="<?php if($Think.ACTION_NAME == 'assess'){echo 'active' ;} ?>">综合评估</li></a>
                <a href="<?php echo U('classroom/collect');?>"><li class="<?php if($Think.ACTION_NAME == 'collect'){echo 'active' ;} ?>">课堂收藏</li></a>
                <?php if(I("typeid") == "1" or I("typeid") == "2" or I("typeid") == "3" or I("typeid") == "4" or I("typeid") == "5"): ?><a href="<?php echo U('classroom/customizationa',array('typeid'=>1));?>"><li class="<?php if($Think.ACTION_NAME == 'customizationa'){echo 'active' ;} ?>">课堂订制</li></a>
                 <?php else: ?>
                     <a href="<?php echo U('classroom/customizationa',array('typeid'=>1));?>"><li class="<?php if($Think.ACTION_NAME == 'customizationa'){echo 'active' ;} ?>">课堂订制</li></a><?php endif; ?>
                <?php if(I("typeid") == "6" or I("typeid") == "7" or I("typeid") == "8" or I("typeid") == "9" or I("typeid") == "10"): ?><a href="<?php echo U('classroom/customizationa',array('typeid'=>1));?>"><li class="<?php if($Think.ACTION_NAME == 'customizationb'){echo 'active' ;} ?>">课堂订制</li></a><?php endif; ?>
                <?php if(I("typeid") == "11" or I("typeid") == "12" or I("typeid") == "13" or I("typeid") == "14" or I("typeid") == "15"): ?><a href="<?php echo U('classroom/customizationa',array('typeid'=>1));?>"><li class="<?php if($Think.ACTION_NAME == 'customizationc'){echo 'active' ;} ?>">课堂订制</li></a><?php endif; ?>
               
                <a href="<?php echo U('classroom/share');?>"><li class="<?php if($Think.ACTION_NAME == 'share'){echo 'active' ;} ?>">分享课堂</li></a>
            </ul>
        </div>

        <!--二维码-->
        <div class="qrcode">
            <img src="/www/slcms/Public/home/images/usercenter/qrcode.png" alt=""/>
            <p>扫描二维码登录手机端</p>
        </div>
    </div>
    <!--主导航-->
     <div id="mainnav">
        <div class="wrap">
            <ul class="left">
                <a href="<?php echo U('index/index');?>" target="_blank"><li>首页</li></a>
                <a href="<?php echo U('index/index');?>" target="_blank"><li>桑磊题库</li></a>
                <a href="<?php echo U('Video/index');?>" target="_blank"><li>法考课堂</li></a>
                <a href="#" target="_blank"><li>赛事活动</li></a>
                <a href="<?php echo U('Band/index');?>" target="_blank"><li>题霸打榜</li></a>
                <a href="<?php echo U('Online/index');?>" target="_blank"><li>在线交流</li></a>
            </ul>
            <a href="#" class="right account">
                <img src="/www/slcms/Public/home/images/nav/user-gray.png" alt=""/>
                会员账户&gt;
            </a>
        </div>
    </div>
    <!--基本资料   这个元素里面的东西不一样-->
    <div class="content-box">
        <!--********-->
        <div id="shareclass">
            <div class="vip"><img src="/www/slcms/Public/home/images/usercenter/ordinary.png" alt=""/></div>
            <!--分享课堂的菜单-->
            <div class="sc-nav">
                <div  class="sc-nav-list active">
                    <div class="img"></div>
                    <div class="text"> 已订制的课堂<span>（369）</span></div>
                </div>
                <div  class="sc-nav-list">
                  <a href="<?php echo U('classroom/customizationb',array('typeid'=>6));?>" style=" text-decoration:none;out-line: none;color: #000;">  <div class="img"></div>
                     私人订制的课堂 <span>（369）</span></a>
                </div>
                <div  class="sc-nav-list">
                     <a href="<?php echo U('classroom/customizationc',array('typeid'=>11));?>" style=" text-decoration:none;out-line: none;color: #000;"><div class="img"></div>
                    众筹订制的课堂 <span>（369）</span></a>
                </div>
            </div>
            <div id="sc-box">
                <!--已订制的课堂-->
                <div style="" class="show sc-buyedclass">
                    <!--用户名  章节分类-->
                    <div class="sc-title">
                        <div class="left">亲爱的 <span class="active">大法官123</span>，您订制的视频如下：</div>
                        <div class="right">
                            <a href="<?php echo U('customizationa',array('typeid'=>1));?>" <?php if(I("typeid") == "1"): ?>class="active"<?php endif; ?>>全部</a>
                            <span>丨</span>
                            <a href="<?php echo U('customizationa',array('typeid'=>2));?>"<?php if(I("typeid") == "2"): ?>class="active"<?php endif; ?>>按章</a>
                            <span>丨</span>
                            <a href="<?php echo U('customizationa',array('typeid'=>3));?>"<?php if(I("typeid") == "3"): ?>class="active"<?php endif; ?>>按节</a>
                            <span>丨</span>
                            <a href="<?php echo U('customizationa',array('typeid'=>4));?>"<?php if(I("typeid") == "4"): ?>class="active"<?php endif; ?>>按知识点</a>
                            <span>丨</span>
                            <a href="<?php echo U('customizationa',array('typeid'=>5));?>" <?php if(I("typeid") == "5"): ?>class="active"<?php endif; ?>>微课堂</a>
                        </div>
                    </div>
                    <!--内容-->
                    <div class="sc-content">
                        <ul>
                            <?php if(is_array($custom)): $i = 0; $__LIST__ = $custom;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><li>
                                <img src="/www/slcms/Public/home/images/usercenter/fxkt/<?php echo ($val['shi']); ?>.png" alt=""/>
                                <p class="name">
                            <?php if($val["name"] != ""): echo ($val['name']); ?>
                                <?php else: ?>
                                <?php echo ($val['did']); endif; ?></p>
                                <div class="time">
                                    <div class="left">浏览：13次</div>
                                    <div class="right">[<?php echo ($val['course']); ?>]</div>
                                </div>
                                <div class="dzkt">
                                    <a href="#">分享视频</a>
                                    <a href="#">观看视频</a>
                                </div>
                            </li><?php endforeach; endif; else: echo "" ;endif; ?>
                            
                            <div class="clearfix"></div>
                        </ul>
                    </div>
                    <!--页码-->
                    <div class="sc-page">
                       <?php echo ($page); ?>
                    </div>
                </div>
                <!--私人订制课堂-->
                <div style="display: none" class="show sc-myclass">
                    <!--用户名  章节分类-->
                    <div class="sc-title">
                        <div class="left">亲爱的 <span class="active">大法官123</span>，您预约订制的视频如下：</div>
                        <div class="right">
                            <a href="<?php echo U('customization',array('typeid'=>6));?>"<?php if(I("typeid") == "6"): ?>class="active"<?php endif; ?>>全部</a>
                            <span>丨</span>
                            <a href="<?php echo U('customization',array('typeid'=>7));?>"<?php if(I("typeid") == "7"): ?>class="active"<?php endif; ?>>按章</a>
                            <span>丨</span>
                            <a href="<?php echo U('customization',array('typeid'=>8));?>"<?php if(I("typeid") == "8"): ?>class="active"<?php endif; ?>>按节</a>
                            <span>丨</span>
                            <a href="<?php echo U('customization',array('typeid'=>9));?>" <?php if(I("typeid") == "9"): ?>class="active"<?php endif; ?>>按知识点</a>
                            <span>丨</span>
                            <a href="<?php echo U('customization',array('typeid'=>10));?>"<?php if(I("typeid") == "10"): ?>class="active"<?php endif; ?>>微课堂</a>
                        </div>
                    </div>
                    <!--内容-->
                    <div class="sc-content">
                        <ul>
                             <?php if(is_array($custompri)): $i = 0; $__LIST__ = $custompri;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$valu): $mod = ($i % 2 );++$i;?><li>
                                <img src="/www/slcms/Public/home/images/usercenter/fxkt/zsd.png" alt=""/>
                                <p class="name">
                            <?php if($valu["name"] != ""): echo ($value['name']); ?>
                                <?php else: ?>
                                <?php echo ($value['did']); endif; ?></p>
                                <div class="time">
                                    <div class="left">预约时间：2017/10/22</div>
                                    <div class="right">[国际经济法]</div>
                                </div>
                                <div>
                                    <a href="#">状态：等待安排课程</a>
                                </div>
                            </li><?php endforeach; endif; else: echo "" ;endif; ?>
                           
                            <div class="clearfix"></div>
                        </ul>
                    </div>
                    <!--页码-->
                    <div class="sc-page">
                        <img src="/www/slcms/Public/home/images/library-list/page.jpg" alt=""/>
                    </div>
                </div>
                <!--众筹订制的课堂-->
                <div style="display: none" class="show sc-zdclass">
                    <!--用户名  章节分类-->
                    <div class="sc-title">
                        <div class="left">亲爱的 <span class="active">大法官123</span>，您参与众筹订制的课堂如下：</div>
                        <div class="right">
                            <a href="<?php echo U('customization',array('typeid'=>11));?>"<?php if(I("typeid") == "11"): ?>class="active"<?php endif; ?>>全部</a>
                            <span>丨</span>
                            <a href="<?php echo U('customization',array('typeid'=>12));?>"<?php if(I("typeid") == "12"): ?>class="active"<?php endif; ?>>按章</a>
                            <span>丨</span>
                            <a href="<?php echo U('customization',array('typeid'=>13));?>"<?php if(I("typeid") == "13"): ?>class="active"<?php endif; ?>>按节</a>
                            <span>丨</span>
                            <a href="<?php echo U('customization',array('typeid'=>14));?>" <?php if(I("typeid") == "14"): ?>class="active"<?php endif; ?>>按知识点</a>
                            <span>丨</span>
                            <a href="<?php echo U('customization',array('typeid'=>15));?>"<?php if(I("typeid") == "15"): ?>class="active"<?php endif; ?>>微课堂</a>
                        </div>
                    </div>
                    <!--内容-->
                    <div class="sc-content">
                        <ul>
                             <?php if(is_array($customcom)): $i = 0; $__LIST__ = $customcom;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$value): $mod = ($i % 2 );++$i;?><li>
                                <img src="/www/slcms/Public/home/images/usercenter/fxkt/zsd.png" alt=""/>
                                <p class="name">
                            <?php if($value["name"] != ""): echo ($value['name']); ?>
                                <?php else: ?>
                                <?php echo ($value['did']); endif; ?></p>
                                <div class="time">
                                    <div class="left">参与人数：<span class="active">3</span>/5&nbsp;&nbsp;等待中...</div>
                                    <div class="right">[国际经济法]</div>
                                </div>
                                <div class="dzkt">
                                    <a href="#">邀请他人订制</a>
                                    <a href="#">继续参与</a>
                                </div>
                                <!--<a href="#">邀请他人订制</a>-->
                            </li><?php endforeach; endif; else: echo "" ;endif; ?>
                       
                            <div class="clearfix"></div>
                        </ul>
                    </div>
                    <!--页码-->
                    <div class="sc-page">
                        <img src="/www/slcms/Public/home/images/library-list/page.jpg" alt=""/>
                    </div>
                </div>
            </div>
            <!--注释-->
            <div class="sc-note">
                * 注：订制后的课堂可在 <a href="#">分享课堂资源站</a> 默认用于公开分享，分享课堂可帮助您带来分享收益，按半价收取每堂课。已购买的分享课堂不可继续交易。
            </div>
        </div>
        <!--********-->
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
<script>
//    购买课堂菜单显示
//    function scNav(t){
//        var i = $(t).index();
//        $("#sc-box .show:eq("+i+")").show().siblings(".show").hide();
//        $(t).addClass("active").siblings(".sc-nav-list").removeClass("active");
//    }
</script>
</body>
</html>