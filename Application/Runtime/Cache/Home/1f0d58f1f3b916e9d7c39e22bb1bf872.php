<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title>用户中心-分享课堂</title>
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
                <div onclick="scNav(this)" class="sc-nav-list active">
                    <div class="img"></div>
                    <div class="text">已购买的分享课堂 <span>（369）</span></div>
                </div>
                <div onclick="scNav(this)" class="sc-nav-list">
                    <div class="img"></div>
                    我的分享课堂 <span>（369）</span>
                </div>
                <div onclick="scNav(this)" class="sc-nav-list">
                    <div class="img"></div>
                    分享课堂资源站 <span>（369）</span>
                </div>
            </div>
            <div id="sc-box">
                <!--已购买的分享课堂-->
                <div  class="show sc-buyedclass">
                    <!--用户名  章节分类-->
                    <div class="sc-title">
                        <div class="left">亲爱的 <span class="active">大法官123</span>，您已购买的分享课堂如下：</div>
                        <div class="right">
                            <a href="#">全部</a>
                            <span>丨</span>
                            <a href="#">按章节</a>
                            <span>丨</span>
                            <a href="#" class="active">按知识点</a>
                            <span>丨</span>
                            <a href="#">微课堂</a>
                        </div>
                    </div>
                    <!--内容-->
                    <div class="sc-content">
                        <ul>
                            <li>
                                <img src="/www/slcms/Public/home/images/usercenter/fxkt/zsd.png" alt=""/>
                                <p class="name">国际货物运输保险合同的原则</p>
                                <div class="time">
                                    <div class="left">购买时间：2017/10/22</div>
                                    <div class="right">[国际经济法]</div>
                                </div>
                                <a href="#">观看视频</a>
                            </li>
                            <li>
                                <img src="/www/slcms/Public/home/images/usercenter/fxkt/zsd.png" alt=""/>
                                <p class="name">国际货物运输保险合同的原则</p>
                                <div class="time">
                                    <div class="left">购买时间：2017/10/22</div>
                                    <div class="right">[国际经济法]</div>
                                </div>
                                <a href="#">观看视频</a>
                            </li>
                            <li>
                                <img src="/www/slcms/Public/home/images/usercenter/fxkt/zsd.png" alt=""/>
                                <p class="name">国际货物运输保险合同的原则</p>
                                <div class="time">
                                    <div class="left">购买时间：2017/10/22</div>
                                    <div class="right">[国际经济法]</div>
                                </div>
                                <a href="#">观看视频</a>
                            </li>
                            <li>
                                <img src="/www/slcms/Public/home/images/usercenter/fxkt/zsd.png" alt=""/>
                                <p class="name">国际货物运输保险合同的原则</p>
                                <div class="time">
                                    <div class="left">购买时间：2017/10/22</div>
                                    <div class="right">[国际经济法]</div>
                                </div>
                                <a href="#">观看视频</a>
                            </li>
                            <li>
                                <img src="/www/slcms/Public/home/images/usercenter/fxkt/zsd.png" alt=""/>
                                <p class="name">国际货物运输保险合同的原则</p>
                                <div class="time">
                                    <div class="left">购买时间：2017/10/22</div>
                                    <div class="right">[国际经济法]</div>
                                </div>
                                <a href="#">观看视频</a>
                            </li>
                            <li>
                                <img src="/www/slcms/Public/home/images/usercenter/fxkt/zsd.png" alt=""/>
                                <p class="name">国际货物运输保险合同的原则</p>
                                <div class="time">
                                    <div class="left">购买时间：2017/10/22</div>
                                    <div class="right">[国际经济法]</div>
                                </div>
                                <a href="#">观看视频</a>
                            </li>
                            <li>
                                <img src="/www/slcms/Public/home/images/usercenter/fxkt/zsd.png" alt=""/>
                                <p class="name">国际货物运输保险合同的原则</p>
                                <div class="time">
                                    <div class="left">购买时间：2017/10/22</div>
                                    <div class="right">[国际经济法]</div>
                                </div>
                                <a href="#">观看视频</a>
                            </li>
                            <li>
                                <img src="/www/slcms/Public/home/images/usercenter/fxkt/zsd.png" alt=""/>
                                <p class="name">国际货物运输保险合同的原则</p>
                                <div class="time">
                                    <div class="left">购买时间：2017/10/22</div>
                                    <div class="right">[国际经济法]</div>
                                </div>
                                <a href="#">观看视频</a>
                            </li>
                            <li>
                                <img src="/www/slcms/Public/home/images/usercenter/fxkt/zsd.png" alt=""/>
                                <p class="name">国际货物运输保险合同的原则</p>
                                <div class="time">
                                    <div class="left">购买时间：2017/10/22</div>
                                    <div class="right">[国际经济法]</div>
                                </div>
                                <a href="#">观看视频</a>
                            </li>
                            <div class="clearfix"></div>
                        </ul>
                    </div>
                    <!--页码-->
                    <div class="sc-page">
                        <img src="/www/slcms/Public/home/images/library-list/page.jpg" alt=""/>
                    </div>
                </div>
                <!--我的分享课堂-->
                <div style="display: none" class="show sc-myclass">
                    <!--用户名  章节分类-->
                    <div class="sc-title">
                        <div class="left">亲爱的 <span class="active">大法官123</span>，您的分享课堂如下：</div>
                        <div class="right">
                            <a href="#">全部</a>
                            <span>丨</span>
                            <a href="#">按章节</a>
                            <span>丨</span>
                            <a href="#" class="active">按知识点</a>
                            <span>丨</span>
                            <a href="#">微课堂</a>
                        </div>
                    </div>
                    <!--内容-->
                    <div class="sc-content">
                        <ul>
                            <li>
                                <img src="/www/slcms/Public/home/images/usercenter/fxkt/zsd.png" alt=""/>
                                <p class="name">国际货物运输保险合同的原则</p>
                                <div class="time">
                                    <div class="left">已购买：598次</div>
                                    <div class="right">[国际经济法]</div>
                                </div>
                                <div class="myclass">
                                    <a href="#">其他分享</a>
                                    <a href="#">查看视频</a>
                                    <a href="#">取消分享</a>
                                </div>
                            </li>
                            <li>
                                <img src="/www/slcms/Public/home/images/usercenter/fxkt/zsd.png" alt=""/>
                                <p class="name">国际货物运输保险合同的原则</p>
                                <div class="time">
                                    <div class="left">已购买：598次</div>
                                    <div class="right">[国际经济法]</div>
                                </div>
                                <div class="myclass">
                                    <a href="#">其他分享</a>
                                    <a href="#">查看视频</a>
                                    <a href="#">取消分享</a>
                                </div>
                            </li>
                            <li>
                                <img src="/www/slcms/Public/home/images/usercenter/fxkt/zsd.png" alt=""/>
                                <p class="name">国际货物运输保险合同的原则</p>
                                <div class="time">
                                    <div class="left">已购买：598次</div>
                                    <div class="right">[国际经济法]</div>
                                </div>
                                <div class="myclass">
                                    <a href="#">其他分享</a>
                                    <a href="#">查看视频</a>
                                    <a href="#">取消分享</a>
                                </div>
                            </li>
                            <li>
                                <img src="/www/slcms/Public/home/images/usercenter/fxkt/zsd.png" alt=""/>
                                <p class="name">国际货物运输保险合同的原则</p>
                                <div class="time">
                                    <div class="left">已购买：598次</div>
                                    <div class="right">[国际经济法]</div>
                                </div>
                                <div class="myclass">
                                    <a href="#">其他分享</a>
                                    <a href="#">查看视频</a>
                                    <a href="#">取消分享</a>
                                </div>
                            </li>
                            <li>
                                <img src="/www/slcms/Public/home/images/usercenter/fxkt/zsd.png" alt=""/>
                                <p class="name">国际货物运输保险合同的原则</p>
                                <div class="time">
                                    <div class="left">已购买：598次</div>
                                    <div class="right">[国际经济法]</div>
                                </div>
                                <div class="myclass">
                                    <a href="#">其他分享</a>
                                    <a href="#">查看视频</a>
                                    <a href="#">取消分享</a>
                                </div>
                            </li>
                            <li>
                                <img src="/www/slcms/Public/home/images/usercenter/fxkt/zsd.png" alt=""/>
                                <p class="name">国际货物运输保险合同的原则</p>
                                <div class="time">
                                    <div class="left">已购买：598次</div>
                                    <div class="right">[国际经济法]</div>
                                </div>
                                <div class="myclass">
                                    <a href="#">其他分享</a>
                                    <a href="#">查看视频</a>
                                    <a href="#">取消分享</a>
                                </div>
                            </li>
                            <li>
                                <img src="/www/slcms/Public/home/images/usercenter/fxkt/zsd.png" alt=""/>
                                <p class="name">国际货物运输保险合同的原则</p>
                                <div class="time">
                                    <div class="left">已购买：598次</div>
                                    <div class="right">[国际经济法]</div>
                                </div>
                                <div class="myclass">
                                    <a href="#">其他分享</a>
                                    <a href="#">查看视频</a>
                                    <a href="#">取消分享</a>
                                </div>
                            </li>
                            <li>
                                <img src="/www/slcms/Public/home/images/usercenter/fxkt/zsd.png" alt=""/>
                                <p class="name">国际货物运输保险合同的原则</p>
                                <div class="time">
                                    <div class="left">已购买：598次</div>
                                    <div class="right">[国际经济法]</div>
                                </div>
                                <div class="myclass">
                                    <a href="#">其他分享</a>
                                    <a href="#">查看视频</a>
                                    <a href="#">取消分享</a>
                                </div>
                            </li>
                            <li>
                                <img src="/www/slcms/Public/home/images/usercenter/fxkt/zsd.png" alt=""/>
                                <p class="name">国际货物运输保险合同的原则</p>
                                <div class="time">
                                    <div class="left">已购买：598次</div>
                                    <div class="right">[国际经济法]</div>
                                </div>
                                <div class="myclass">
                                    <a href="#">其他分享</a>
                                    <a href="#">查看视频</a>
                                    <a href="#">取消分享</a>
                                </div>
                            </li>
                            <li>
                                <img src="/www/slcms/Public/home/images/usercenter/fxkt/zsd.png" alt=""/>
                                <p class="name">国际货物运输保险合同的原则</p>
                                <div class="time">
                                    <div class="left">购买时间：2017/10/22</div>
                                    <div class="right">[国际经济法]</div>
                                </div>
                                <div class="myclass">
                                    <a href="#">其他分享</a>
                                    <a href="#">查看视频</a>
                                    <a href="#">取消分享</a>
                                </div>
                            </li>
                            <div class="clearfix"></div>
                        </ul>
                    </div>
                    <!--页码-->
                    <div class="sc-page">
                        <img src="/www/slcms/Public/home/images/library-list/page.jpg" alt=""/>
                    </div>
                </div>
                <!--分享课堂资源站-->
                <div style="display: none" class="show sc-zdclass">
                    <!--用户名  章节分类-->
                    <div class="sc-title">
                        <div class="left">
                            <form action="">
                                <div class="sc-search">
                                    <input type="text" name="" value="" placeholder="请搜索关键字检索"/>
                                    <button type="submit">搜索</button>
                                </div>
                            </form>
                        </div>
                        <div class="right">
                            <a href="#">全部</a>
                            <span>丨</span>
                            <a href="#">按章节</a>
                            <span>丨</span>
                            <a href="#" class="active">按知识点</a>
                            <span>丨</span>
                            <a href="#">微课堂</a>
                        </div>
                    </div>
                    <!--内容-->
                    <div class="sc-content">
                        <ul>
                            <li>
                                <img src="/www/slcms/Public/home/images/usercenter/fxkt/zsd.png" alt=""/>
                                <p class="name">国际货物运输保险合同的原则</p>
                                <div class="time">
                                    <div class="left">分享来源：小蜜蜂69</div>
                                    <div class="right">[国际经济法]</div>
                                </div>
                                <a href="#">购买视频</a>
                            </li>
                            <li>
                                <img src="/www/slcms/Public/home/images/usercenter/fxkt/zsd.png" alt=""/>
                                <p class="name">国际货物运输保险合同的原则</p>
                                <div class="time">
                                    <div class="left">分享来源：小蜜蜂69</div>
                                    <div class="right">[国际经济法]</div>
                                </div>
                                <a href="#">购买视频</a>
                            </li>
                            <li>
                                <img src="/www/slcms/Public/home/images/usercenter/fxkt/zsd.png" alt=""/>
                                <p class="name">国际货物运输保险合同的原则</p>
                                <div class="time">
                                    <div class="left">分享来源：小蜜蜂69</div>
                                    <div class="right">[国际经济法]</div>
                                </div>
                                <a href="#">购买视频</a>
                            </li>
                            <li>
                                <img src="/www/slcms/Public/home/images/usercenter/fxkt/zsd.png" alt=""/>
                                <p class="name">国际货物运输保险合同的原则</p>
                                <div class="time">
                                    <div class="left">分享来源：小蜜蜂69</div>
                                    <div class="right">[国际经济法]</div>
                                </div>
                                <a href="#">购买视频</a>
                            </li>
                            <li>
                                <img src="/www/slcms/Public/home/images/usercenter/fxkt/zsd.png" alt=""/>
                                <p class="name">国际货物运输保险合同的原则</p>
                                <div class="time">
                                    <div class="left">分享来源：小蜜蜂69</div>
                                    <div class="right">[国际经济法]</div>
                                </div>
                                <a href="#">购买视频</a>
                            </li>
                            <li>
                                <img src="/www/slcms/Public/home/images/usercenter/fxkt/zsd.png" alt=""/>
                                <p class="name">国际货物运输保险合同的原则</p>
                                <div class="time">
                                    <div class="left">分享来源：小蜜蜂69</div>
                                    <div class="right">[国际经济法]</div>
                                </div>
                                <a href="#">购买视频</a>
                            </li>
                            <li>
                                <img src="/www/slcms/Public/home/images/usercenter/fxkt/zsd.png" alt=""/>
                                <p class="name">国际货物运输保险合同的原则</p>
                                <div class="time">
                                    <div class="left">分享来源：小蜜蜂69</div>
                                    <div class="right">[国际经济法]</div>
                                </div>
                                <a href="#">购买视频</a>
                            </li>
                            <li>
                                <img src="/www/slcms/Public/home/images/usercenter/fxkt/zsd.png" alt=""/>
                                <p class="name">国际货物运输保险合同的原则</p>
                                <div class="time">
                                    <div class="left">分享来源：小蜜蜂69</div>
                                    <div class="right">[国际经济法]</div>
                                </div>
                                <a href="#">购买视频</a>
                            </li>
                            <li>
                                <img src="/www/slcms/Public/home/images/usercenter/fxkt/zsd.png" alt=""/>
                                <p class="name">国际货物运输保险合同的原则</p>
                                <div class="time">
                                    <div class="left">分享来源：小蜜蜂69</div>
                                    <div class="right">[国际经济法]</div>
                                </div>
                                <a href="#">购买视频</a>
                            </li>
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
    function scNav(t){
        var i = $(t).index();
        $("#sc-box .show:eq("+i+")").show().siblings(".show").hide();
        $(t).addClass("active").siblings(".sc-nav-list").removeClass("active");
    }
</script>
</body>
</html>