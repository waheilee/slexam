<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title>桑磊法考</title>
    <meta charset="UTF-8">
    <meta http-equiv="expires" content="0">
    <meta http-equiv="pragma" content="no-cache">
    <meta http-equiv="cache-control" content="no-cache">
    <meta name="keyword" content="桑磊法考,司法考试,考试,司法,律师">
    <!-- 公用的css -->
    <link rel="stylesheet" href="/www/slcms/Public/home/css/public.css"/>
    <!-- 本页面的css -->
    <link rel="stylesheet" href="/www/slcms/Public/home/css/index.css"/>
</head>
<body>
<!--主导航-->
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
<!--banner轮播-->
<div id="banner">
    <!--标题背景-->
    <div id="banner_bg" style="display: none">
        <div class="wrap">
            <div style="margin-left: 195px;float: left;">
                <!--标题(图片所带的标题放在下面图片的alt里面)-->
                <div id="banner_info" class=""left></div>
                <div class="clearfix"></div>
            </div>
            <ul class="right">
                <li class="on">1</li>
                <li>2</li>
                <li>3</li>
            </ul>
            <div class="clearfix"></div>
        </div>
    </div>
    <div id="banner_list">
        <!--注意banner图的路径放在div的style里面-->
        <a href="#1" target="_blank"><div style="background: url('/www/slcms/Public/home/images/index/banner/banner.jpg') no-repeat center center;"  class="bannerimgbox"></div></a>
        <a href="#2" target="_blank"><div style="background: url('/www/slcms/Public/home/images/index/banner/banner-1.jpg') no-repeat center center;" class="bannerimgbox"></div></a>
        <a href="#3" target="_blank"><div style="background: url('/www/slcms/Public/home/images/index/banner/banner-2.jpg') no-repeat center center;" class="bannerimgbox"></div></a>
    </div>
</div>
<!--上导航-->
<div id="topnav">
    <div class="wrap">
        <ul>
            <!--桑磊题库-->
            <li class="sltk">
                <p class="title">精选题库提纲</p>
                <p><span>12</span>门</p>
                <p><span><?php echo ($count); ?></span>个题目</p>
                <p>核心模拟真题评估</p>
                <a id="turnToContent" href="#content" class="href" >
                    桑磊题库
                </a>
            </li>
            <!--法考课堂-->
            <li class="fkkt">
                <p class="title">在线法考辅导</p>
                <p>各大难点题型辅导</p>
                <p>在线订制教学视频</p>
                <p>免费视频教学解析</p>
                <a href="<?php echo U('Video/index');?>" class="href" target="_blank">
                    法考课堂
                </a>
                <a href="#" class="dzkt">
                    <img src="/www/slcms/Public/home/images/index/icon/dzkt.png" alt=""/>
                </a>
            </li>
            <!--题霸打榜-->
            <li class="tbdb">
                <p class="title">打榜有赏机制</p>
                <p>等级奖励犒赏</p>
                <p>邀请好友PK</p>
                <p>区域赛事对接</p>
                <a href="<?php echo U('Band/index');?>" class="href" target="_blank">
                    题霸打榜
                </a>
            </li>
            <div class="clearfix"></div>
        </ul>
    </div>
</div>
<!--视频菜单-->
<div id="videomenu">
    <ul class="wrap">
        <li>
            <div class="top">
                <?php if(is_array($video['course'])): $i = 0; $__LIST__ = array_slice($video['course'],0,1,true);if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><img src="/www/slcms/Public/home/images/index/video/1.png" alt=""/>
                    <p class="title">课程：<?php echo ($vo['title']); ?></p>
                    <!--正在播出加playing这个class，反之不加-->
                    <p class="isplay playing">正在播出</p>
                    <a href="<?php echo U('video/videoclass',array('id'=>$vo['id']));?>" target="_blank" class="golook">免费观看</a><?php endforeach; endif; else: echo "" ;endif; ?>
            </div>
            <div class="content">
                <?php if(is_array($video['course'])): $i = 0; $__LIST__ = array_slice($video['course'],1,3,true);if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><a  href="<?php echo U('video/videoclass',array('id'=>$vo['id']));?>" target="_blank" class="list">
                        <img src="/www/slcms/Public/home/images/index/icon/play.png" alt=""/>
                        <?php echo ($vo['title']); ?>
                    </a>
                    <a href="<?php echo U('video/videoclass');?>" target="_blank" class="lookhotvideo">
                        查看精品课程视频
                    </a><?php endforeach; endif; else: echo "" ;endif; ?>
            </div>
        </li>
        <li>
            <div class="top">
                <?php if(is_array($video['groom'])): $i = 0; $__LIST__ = array_slice($video['groom'],0,1,true);if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><img src="/www/slcms/Public/home/images/index/video/2.png" alt=""/>
                    <p class="title">精品：<?php echo ($vo['title']); ?></p>
                    <!--正在播出加playing这个class，反之不加-->
                    <p class="isplay">正在播出</p>
                    <a href="<?php echo U('video/boutique',array('id'=>$vo['id']));?>" target="_blank" class="golook">免费观看</a><?php endforeach; endif; else: echo "" ;endif; ?>
            </div>
            <div class="content">
                <?php if(is_array($video['groom'])): $i = 0; $__LIST__ = array_slice($video['groom'],1,3,true);if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><a  href="<?php echo U('video/boutique',array('id'=>$vo['id']));?>" target="_blank" class="list">
                        <img src="/www/slcms/Public/home/images/index/icon/play.png" alt=""/>
                        <?php echo ($vo['title']); ?>
                    </a><?php endforeach; endif; else: echo "" ;endif; ?>
                <a href="<?php echo U('video/boutique');?>" target="_blank" class="lookhotvideo">
                    查看精品推荐视频
                </a>
            </div>
        </li>
        <li>
            <div class="top">
                <p class="title nobg">在线订制教学视频</p>
                <!--正在播出加playing这个class，反之不加-->
                <p class="text">题库难点、知识点 <br/>一对一教学&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;订制收益</p>
                <a href="<?php echo U('Video/madeVideo');?>" target="_blank" class="golook">我要订制</a>
            </div>
            <div class="content">
                <a  href="<?php echo U('Video/madeVideo');?>" target="_blank" class="list">
                    <!--这个图片和所有图片不一样 订制-->
                    <img src="/www/slcms/Public/home/images/index/icon/dz.png" alt=""/>
                    在线订制视频与分享订制收益
                </a>
                <a  href="<?php echo U('Video/madeVideo');?>" target="_blank" class="list">
                    <!--这个图片和所有图片不一样  参与-->
                    <img src="/www/slcms/Public/home/images/index/icon/cy.png" alt=""/>
                    参与预约订制视频
                    <!--这个图片和所有图片不一样  抢-->
                    <img src="/www/slcms/Public/home/images/index/icon/hot.png" alt=""/>
                </a>
                <a href="<?php echo U('video/index');?>" target="_blank" class="lookhotvideo">
                    查看热点视频
                </a>
            </div>
        </li>
        <div class="clearfix"></div>
    </ul>
</div>
<!--桑累法考题库-->
<div id="questionbank">
    <div id="content">
    <p class="onetitle">桑磊法考题库集</p>
    <p class="onetext">12门法考题型&nbsp;&nbsp;&nbsp;<?php echo ($count); ?>个题目&nbsp;&nbsp;&nbsp;核心模拟真题评估</p>
    <div class="wrap" style="position: relative;">
        <div class="banner" id="b04">
            <ul>
                <!--第1页题库-->
                <li>
                    <?php if(is_array($data)): $k = 0; $__LIST__ = $data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k;?><a href="<?php echo U('Examin/index',array('code'=>base64_encode($vo['id'])));?>" class="library-list" target="_blank">
                            <img src="/www/slcms/Public/home/images/index/library/<?php echo ($k); ?>.jpg" alt=""/>
                            <p class="name"><?php echo ($vo['name']); ?>题库</p>
                            <p><?php echo ((isset($vo['point'] ) && ($vo['point'] !== ""))?($vo['point'] ):0); ?>个知识点，<?php echo ((isset($vo['quest'] ) && ($vo['quest'] !== ""))?($vo['quest'] ):0); ?>个测试题目</p>
                        </a>
                        <?php if((($k % 3) == 0) && ($data[$k+1]['id'] != null)): ?></li>
                            <li><?php endif; endforeach; endif; else: echo "" ;endif; ?>
                   
                </li>
            </ul>
        </div>
        <!--上一页-->
        <a href="javascript:void(0);" class="unslider-arrow04 prev"><img class="arrow" id="al" src="/www/slcms/Public/home/images/index/icon/banner-prev.png" ></a>
        <!--下一页-->
        <a href="javascript:void(0);" class="unslider-arrow04 next"><img class="arrow" id="ar" src="/www/slcms/Public/home/images/index/icon/banner-next.png" ></a>
        <!--第5页题库-->
        <ul class="fix">
            <li>
                <a href="#" class="library-list" target="_blank">
                    <img src="/www/slcms/Public/home/images/index/library/lnztk.jpg" alt=""/>
                    <p class="name">真题试题</p>
                    <p></p>
                    <!-- <p>10个知识点，58个测试题目</p> -->
                </a>
                <a href="<?php echo U('Random/analog');?>" class="library-list" target="_blank">
                    <img src="/www/slcms/Public/home/images/index/library/mnztk.jpg" alt=""/>
                    <p class="name">模拟试题</p>
                    <p></p>
                    <!-- <p>10个知识点，58个测试题目</p> -->
                </a>
                <a href="<?php echo U('Random/custom');?>" class="library-list" target="_blank">
                    <img src="/www/slcms/Public/home/images/index/library/zdytk.jpg" alt=""/>
                    <p class="name">自定义试题</p>
                    <p></p>
                    <!-- <p>10个知识点，58个测试题目</p> -->
                </a>
                <div class="clearfix"></div>
            </li>
        </ul>
        <!--随手题库-->
        <!-- <a href="<?php echo U('Random/index');?>" target="_blank" class="handlibrary">
            题霸随手题库
            <span>（随机/自定义抽选题型）</span>
            <img src="/www/slcms/Public/home/images/index/icon/goblue.png" alt=""/>
        </a> -->
    </div>
    </div>
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
</body>
<script src="/www/slcms/Public/home/js/jquery-1.11.3.js"></script>
<script src="/www/slcms/Public/home/js/unslider.min.js"></script>
<script src="/www/slcms/Public/home/js/index.js"></script>
<script>
    $(function(){
        $('a[href*=#],area[href*=#]').click(function() {
            if (location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') && location.hostname == this.hostname) {
                var $target = $(this.hash);
                $target = $target.length && $target || $('[name=' + this.hash.slice(1) + ']');
                if ($target.length) {
                    var targetOffset = $target.offset().top;
                    $('html,body').animate({
                            scrollTop: targetOffset
                        },
                        1000);
                    return false;
                }
            }
        });
    })
</script>
</html>