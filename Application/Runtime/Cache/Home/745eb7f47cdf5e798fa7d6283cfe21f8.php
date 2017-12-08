<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title>用户中心-账户充值</title>
    <meta charset="UTF-8">
    <meta http-equiv="expires" content="0">
    <meta http-equiv="pragma" content="no-cache">
    <meta http-equiv="cache-control" content="no-cache">
    <meta name=”keyword” content=”桑磊法考,司法考试,考试,司法,律师“>
    <!-- 公用的css -->
    <link rel="stylesheet" href="/www/slcms/Public/home/css/user-center-public.css"/>
    <!-- 本页面的css -->
    <link rel="stylesheet" href="/www/slcms/Public/home/css/user-center-zhcz.css"/>
</head>
<body>
<div class="wrap">
    <!--侧边栏导航-->
    <div id="sidebar-nav">
        <!--头像 用户名-->
         <div class="top">
            <img src="/www/slcms/Public/home/images/usercenter/logo.png" class="logo" alt=""/>
            <div class="avatar">
                 <?php if($user['head'] == ''): ?><img src="/www/slcms/Public/home/images/usercenter/tx.png" alt=""id="imgReLook"/>
                <?php else: ?>
                 <img src="/www/slcms<?php echo ($user['head']); ?>" alt="" width="100" height="100" id="imgReLook"/><?php endif; ?>
            </div>
            <div class="name"><?php echo ($user['username']); ?></div>
            <div class="account">
                账户：[普通账户]
                <img src="/www/slcms/Public/home/images/usercenter/quanone.png" style="margin-top: -7px;" alt=""/>
            </div>
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
        <div id="recharge">
            <div class="vip"><img src="/www/slcms/Public/home/images/usercenter/ordinary.png" alt=""/></div>
            <div class="name-title">
                <div class="left">账户充值（PC）</div>
                <!--兑换法豆的切换-->
                <div class="right">
                    <img onclick="czfdTiggle(this)" src="/www/slcms/Public/home/images/usercenter/zhcz/dhfd.png" alt=""/>
                    <span onclick="czyeTiggle(this)" class="czye">充值余额</span>
                </div>
            </div>
            <!--充值现金-->
            <div class="recharge-dou-style">
                <p class="recharge-style-title">输入兑换的法豆数量：</p>
                <div class="recharge-num">
                    <form action="">
                        <!--最后的金额在这个input里面  获取这个的value-->
                        <input type="hidden" value="" name="" id="dounum"/>
                        <!--*******************-->
                        <button type="button" data="10" onclick="moneyNumOne(this)">100 <span>豆</span> </button>
                        <button type="button" data="20" onclick="moneyNumOne(this)">200 <span>豆</span> </button>
                        <button type="button" data="30" onclick="moneyNumOne(this)">500 <span>豆</span></button>
                        <button type="button" data="150" onclick="moneyNumOne(this)" style="margin-right: 0;">1000 <span>豆</span> </button>
                        <div class="inputbox">
                            <button type="button">自定义数量</button>
                            <input type="number" value="" onblur="moneyNumTwo(this)" onfocus="removeClass(this)"/>
                        </div>
                        <button type="submit" class="gorecharge">开始兑换 <span>（账户将扣除 ¥100）</span></button>
                    </form>
                </div>
            </div>
            <!--兑换法豆-->
            <div class="recharge-style">
                <p class="recharge-style-title">充值方式：</p>
                <div class="recharge-style-chose">
                    <input type="radio" name="" id="chose-zfb"/>
                    <label for="chose-zfb">
                        <img src="/www/slcms/Public/home/images/usercenter/zhcz/zfb.png" alt=""/>
                    </label>
                    <input type="radio" name="" id="chose-wx"/>
                    <label for="chose-wx">
                        <img src="/www/slcms/Public/home/images/usercenter/zhcz/wx.png" alt=""/>
                    </label>
                    <input type="radio" name="" id="chose-wy"/>
                    <label for="chose-wy">
                        <img src="/www/slcms/Public/home/images/usercenter/zhcz/wy.png" style="margin-right: 0;" alt=""/>
                    </label>
                </div>
                <p class="recharge-style-title">充值金额：</p>
                <div class="recharge-num">
                    <form action="">
                        <!--最后的金额在这个input里面  获取这个的value-->
                        <input type="hidden" value="" name="" id="moneynum"/>
                        <!--*******************-->
                        <button type="button" data="10" onclick="moneyNumOne(this)">10元</button>
                        <button type="button" data="20" onclick="moneyNumOne(this)">20元</button>
                        <button type="button" data="30" onclick="moneyNumOne(this)">30元</button>
                        <button type="button" data="50" onclick="moneyNumOne(this)">50元</button>
                        <button type="button" data="150" onclick="moneyNumOne(this)" style="margin-right: 0;">100元</button>
                        <div class="inputbox">
                            <button type="button">其他金额：</button>
                            <input type="number" value="" onblur="moneyNumTwo(this)" onfocus="removeClass(this)"/>
                            元
                        </div>
                        <button type="submit" class="gorecharge">现在充值</button>
                    </form>
                </div>
            </div>
            <!--账户余额-->
            <div class="balance">
                <div class="balance-title">
                    账户余额：
                </div>
                <div class="balance-num">
                    <span>130.00</span>元
                </div>
                <div class="balance-title">
                    拥有法豆：
                </div>
                <div class="balance-num">
                    <span>80</span>个
                </div>
                <p class="text">
                    *新用户登陆首次获得 20个法豆10元=100个法豆
                </p>
            </div>
            <div class="name-title" style="width: 930px;">
                <div class="left">你也可以：</div>
            </div>
            <!--扫码支付-->
            <div class="qrcode">
                <p class="qrcode-title">
                    <span>支付宝支付</span>
                    <span>微信支付</span>
                </p>
                <div class="col-1">
                    <div class="qrcode-name left">
                        扫<br/>码<br/>支<br/>付
                    </div>
                    <!--支付宝二维码-->
                    <div class="qrcode-img left">
                        <img src="/www/slcms/Public/home/images/usercenter/zhcz/zfbqrcode.png" alt=""/>
                    </div>
                    <div class="qrcode-bor left"></div>
                    <!--微信二维码-->
                    <div class="qrcode-img left">
                        <img src="/www/slcms/Public/home/images/usercenter/zhcz/wxqrcode.png" alt=""/>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
            <!--注释-->
            <p class="note">* 注：扫码输入充值金额即时到账。因平台原因可能出现延迟到账。</p>
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
<!--选择金额-->
<script>
    function moneyNumTwo(t){
        $("#moneynum").val($(t).val());

    };
    function removeClass(t){
        $(t).parents(".inputbox").siblings("button").removeClass("active");
    };
    function moneyNumOne(t){
        $("#moneynum").val($(t).attr("data"))
        $(t).addClass("active").siblings("button").removeClass("active").siblings(".inputbox").children("input").val("");
    }
</script>
<!--切换充值法豆和余额-->
<script>
    function czfdTiggle(t){
      $(t).hide().siblings("span").show()
      $("#recharge .recharge-dou-style").show().siblings(".recharge-style").hide();
    };
    function czyeTiggle(t){
        $(t).hide().siblings("img").show()
        $("#recharge .recharge-style").show().siblings(".recharge-dou-style").hide();
    };
</script>
</body>
</html>