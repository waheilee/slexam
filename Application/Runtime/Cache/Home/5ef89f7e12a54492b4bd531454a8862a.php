<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title>用户中心-基本资料</title>
    <meta charset="UTF-8">
    <meta http-equiv="expires" content="0">
    <meta http-equiv="pragma" content="no-cache">
    <meta http-equiv="cache-control" content="no-cache">
    <meta name=”keyword” content=”桑磊法考,司法考试,考试,司法,律师“>
    <!-- 公用的css -->
    <link rel="stylesheet" href="/www/slcms/Public/home/css/user-center-public.css"/>
    <!-- 本页面的css -->
    <link rel="stylesheet" href="/www/slcms/Public/home/css/user-center-jbzl.css"/>
    
    <link rel="stylesheet" href="/www/slcms/Public/home/css/bootstrap.css"/>
 
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
        <div id="datum">
            <div class="vip"><img src="/www/slcms/Public/home/images/usercenter/ordinary.png" alt=""/></div>
            <div class="name-title">基本资料</div>
            <div class="form">
                <form  id="form" method="post" action="" enctype="multipart/form-data">
                        <div class="inputbox">
                            <span>用户昵称：</span><input type="text" value="<?php echo ($user['username']); ?>" id="username" name="username" placeholder="请填写您的昵称" />
                            <span>真实姓名：</span><input class="mr0" type="text" id="truename" value="<?php echo ($user['truename']); ?>" name="truename" placeholder="请填写您的真实姓名"/>
                        </div>
                        <div class="inputbox">
                            <span>您的性别：</span>
                            <!--<input type="text" value="" name="" placeholder="请填写您的性别"/>-->
                            <select name='sex' id="sex" style="width:229px; ">
                                 <option value=''>请选择</option>
                                <option value='1'<?php if($user['sex'] == 1): ?>selected="selected"<?php endif; ?>>男</option>
                                <option value='2' <?php if($user['sex'] == 2): ?>selected="selected"<?php endif; ?>>女</option>
                            </select>
                            <span>联系电话：</span><input  class="mr0" type="text" value="<?php echo ($user['mobile']); ?>" id="mobile" name="mobile" placeholder="请填写您的联系方式" />
                        </div>
                        <div class="inputbox">
                            <span>出生日期：</span><input  id="birthday" placeholder="请填写您的出生日期" name="birthday" type="text" data-date-format="yyyy-mm-dd"  value="<?php if( $user['birthday'] != '' ): echo (date('Y-m-d ',$user['birthday'])); endif; ?>"/>
                            <span>电子邮件：</span><input class="mr0" type="email" value="<?php echo ($user['email']); ?>" id="email" name="email" placeholder="请填写您的电子邮件"/>
                        </div>
                        <div class="inputbox">
                            <span>所在地区：</span>
                            <!--默认选中的option加selected-->
                            <select name="province" id="province">
                                    <option value="" >请选择</option>
                                    <?php if(is_array($province)): $i = 0; $__LIST__ = $province;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option  value="<?php echo ($vo["area_id"]); ?>" <?php if($vo["area_id"] == $user['province']): ?>selected="selected"<?php endif; ?>><?php echo ($vo["area_name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                                </select>
                            <span class="mr5">省</span>
                             <select name="city" id="city">
                                <option value="">请选择</option>
                            </select>
                            <span class="mr5">市</span>
                            <select name="town" id="town">
                                <option value="">请选择</option>
                            </select> 
                            <span class="mr5">区县</span>
                        </div>
                        <!--<div class="fileimg">-->
                            <!--<div id="preview">-->
                                <!--<?php if($user['head'] == ''): ?>-->
                                <!--<img src="/www/slcms/Public/home/images/usercenter/tx.png" alt=""/>-->
                                <!--<?php else: ?>-->
                                 <!--<img src="/www/slcms<?php echo ($user['head']); ?>" alt="" width="220" height="220"/>-->
                                <!--<?php endif; ?>-->
                            <!--</div>-->
                            <!--<div class="file">-->
                                <!--点击修改头像-->
                                <!--<input type="file" name="head" id="head" onchange="preview(this)" value=""/>-->
                            <!--</div>-->
                        <!--</div>-->
                        <div class="inputbox">
                            <button type="submit" id="getsubmit">
                                确认修改 <span>（完善资料赠送20个法豆）</span>
                            </button>
                        </div>
                    </form>
                <div class="fileimg">
                    <div class="file" style="height: 27px;line-height: 28px;border-radius:30px;">
                        点击修改头像
                        <input type="file" name="file" id="fileUpload" onchange="preview(this)" value=""/>
                    </div>
                    <div id="preview">
                        <?php if($user['head'] == ''): ?><img src="/www/slcms/Public/home/images/usercenter/tx.png" alt=""/>
                            <?php else: ?>
                            <img src="/www/slcms<?php echo ($user['head']); ?>" alt="" width="220" height="220"/><?php endif; ?>
                    </div>

                    <div class="" >
                    <input type="button"  value="确认修改" id="imgbutton" style="height: 42px;width: 220px; border-radius: 3px;background: #f5f5f5;"/>
                    </div>
                </div>
            </div>
            <div class="name-title-big">
                <div class="left">账户信息</div>
                <div class="right">
                    <div class="left">
                        账户收支
                    </div>
                    <div class="left prevnext">
                        <img src="/www/slcms/Public/home/images/usercenter/prev-gray.png" onclick="prevIncome()" alt=""/>
                        <img src="/www/slcms/Public/home/images/usercenter/next-gray.png" onclick="nextIncome()" alt=""/>
                    </div>
                    <div class="right">
                        <a class="active" href="javascript:void(0)" onclick="incomeAll(this)">全部</a>
                        <span>丨</span>
                        <a href="javascript:void(0)" onclick="incomeIn(this)">支出</a>
                        <span>丨</span>
                        <a href="javascript:void(0)" onclick="incomeCome(this)">收入</a>
                        <span>丨</span>
                        <a href="javascript:void(0)" onclick="incomeMoney(this)">分享效益</a>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="clearfix"></div>
            </div>
            <!--账户余额-->
            <div class="details">
                <div class="left leftbox">
                    <div class="title">
                        <div class="left">
                            <div class="cell">
                                <div class="name rbor">
                                    账户余额：
                                </div>
                                <div class="num rbor">
                                    <span>130.00</span>元
                                </div>
                            </div>
                        </div>
                        <div class="right">
                            <div class="cell">
                                <div class="name">
                                    拥有法豆：
                                </div>
                                <div class="num">
                                    <span>80</span>个
                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <a href="#">去充值</a>
                    <a href="#">兑换法豆</a>
                    <p class="text">（*新用户登陆首次获得 20个法豆；10元=100个法豆）</p>
                </div>
                <div class="right rightbox">
                    <ul>
                        <!--抬头-->
                        <li class="title">
                            <div class="col-1">日期</div>
                            <div class="col-2">金额</div>
                            <div class="col-3">操作</div>
                        </li>
                        <!--数据循环到每个下面-->
                        <div class="overflowbox">
                            <div class="overflow transition">
                                <!--全部-->
                                <div class="disnone disblock" id="incomeall">
                                    <li>
                                        <div class="col-1">2017-10-24</div>
                                        <div class="col-2">31元</div>
                                        <div class="col-3">精品试题购买</div>
                                    </li>
                                    <li>
                                        <div class="col-1">2017-10-24</div>
                                        <div class="col-2">32元</div>
                                        <div class="col-3">精品试题购买</div>
                                    </li>
                                    <li>
                                        <div class="col-1">2017-10-24</div>
                                        <div class="col-2">33元</div>
                                        <div class="col-3">精品试题购买</div>
                                    </li>
                                    <li>
                                        <div class="col-1">2017-10-24</div>
                                        <div class="col-2">430元</div>
                                        <div class="col-3">精品试题购买</div>
                                    </li>
                                    <li>
                                        <div class="col-1">2017-10-24</div>
                                        <div class="col-2">530元</div>
                                        <div class="col-3">精品试题购买</div>
                                    </li>
                                    <li>
                                        <div class="col-1">2017-10-24</div>
                                        <div class="col-2">630元</div>
                                        <div class="col-3">精品试题购买</div>
                                    </li>
                                    <li>
                                        <div class="col-1">2017-10-24</div>
                                        <div class="col-2">730元</div>
                                        <div class="col-3">精品试题购买</div>
                                    </li>
                                    <li>
                                        <div class="col-1">2017-10-24</div>
                                        <div class="col-2">830元</div>
                                        <div class="col-3">精品试题购买</div>
                                    </li>
                                    <li>
                                        <div class="col-1">2017-10-24</div>
                                        <div class="col-2">930元</div>
                                        <div class="col-3">精品试题购买</div>
                                    </li>
                                    <li>
                                        <div class="col-1">2017-10-24</div>
                                        <div class="col-2">130元</div>
                                        <div class="col-3">精品试题购买</div>
                                    </li>
                                    <li>
                                        <div class="col-1">2017-10-24</div>
                                        <div class="col-2">20元</div>
                                        <div class="col-3">精品试题购买</div>
                                    </li>
                                    <li>
                                        <div class="col-1">2017-10-24</div>
                                        <div class="col-2">30元</div>
                                        <div class="col-3">精品试题购买</div>
                                    </li>
                                    <li>
                                        <div class="col-1">2017-10-24</div>
                                        <div class="col-2">40元</div>
                                        <div class="col-3">精品试题购买</div>
                                    </li>
                                    <li>
                                        <div class="col-1">2017-10-24</div>
                                        <div class="col-2">30元</div>
                                        <div class="col-3">精品试题购买</div>
                                    </li>
                                    <li>
                                        <div class="col-1">2017-10-24</div>
                                        <div class="col-2">888888888888元</div>
                                        <div class="col-3">精品试题购买</div>
                                    </li>
                                    <li>
                                        <div class="col-1">2017-10-24</div>
                                        <div class="col-2">99999999999元</div>
                                        <div class="col-3">精品试题购买</div>
                                    </li>
                                    <li>
                                        <div class="col-1">2017-10-24</div>
                                        <div class="col-2">30元</div>
                                        <div class="col-3">精品试题购买</div>
                                    </li>
                                    <li>
                                        <div class="col-1">2017-10-24</div>
                                        <div class="col-2">200元</div>
                                        <div class="col-3">账户充值</div>
                                    </li>
                                    <li>
                                        <div class="col-1">2017-10-24</div>
                                        <div class="col-2">56566元</div>
                                        <div class="col-3">分享视频收益</div>
                                    </li>
                                    <li>
                                        <div class="col-1">2017-10-24</div>
                                        <div class="col-2">54545元</div>
                                        <div class="col-3">订制视频</div>
                                    </li>
                                    <li>
                                        <div class="col-1">2017-10-24</div>
                                        <div class="col-2">242元</div>
                                        <div class="col-3">精品试题购买</div>
                                    </li>
                                </div>
                                <!--支出-->
                                <div class="disnone" id="incomein">
                                    <li>
                                        <div class="col-1">2017-10-24</div>
                                        <div class="col-2">30000元</div>
                                        <div class="col-3">精品试题购买</div>
                                    </li>
                                    <li>
                                        <div class="col-1">2017-10-24</div>
                                        <div class="col-2">30000元</div>
                                        <div class="col-3">精品试题购买</div>
                                    </li>
                                    <li>
                                        <div class="col-1">2017-10-24</div>
                                        <div class="col-2">30000元</div>
                                        <div class="col-3">精品试题购买</div>
                                    </li>
                                    <li>
                                        <div class="col-1">2017-10-24</div>
                                        <div class="col-2">30000元</div>
                                        <div class="col-3">精品试题购买</div>
                                    </li>
                                    <li>
                                        <div class="col-1">2017-10-24</div>
                                        <div class="col-2">30000元</div>
                                        <div class="col-3">精品试题购买</div>
                                    </li>
                                    <li>
                                        <div class="col-1">2017-10-24</div>
                                        <div class="col-2">30000元</div>
                                        <div class="col-3">精品试题购买</div>
                                    </li>
                                    <li>
                                        <div class="col-1">2017-10-24</div>
                                        <div class="col-2">200元</div>
                                        <div class="col-3">账户充值</div>
                                    </li>
                                    <li>
                                        <div class="col-1">2017-10-24</div>
                                        <div class="col-2">20元</div>
                                        <div class="col-3">分享视频收益</div>
                                    </li>
                                    <li>
                                        <div class="col-1">2017-10-24</div>
                                        <div class="col-2">50元</div>
                                        <div class="col-3">订制视频</div>
                                    </li>
                                    <li>
                                        <div class="col-1">2017-10-24</div>
                                        <div class="col-2">20元</div>
                                        <div class="col-3">精品试题购买</div>
                                    </li>
                                </div>
                                <!--收入-->
                                <div class="disnone" id="incomecome">
                                    <li>
                                        <div class="col-1">2017-10-24</div>
                                        <div class="col-2">3元</div>
                                        <div class="col-3">精品试题购买</div>
                                    </li>
                                    <li>
                                        <div class="col-1">2017-10-24</div>
                                        <div class="col-2">2元</div>
                                        <div class="col-3">账户充值</div>
                                    </li>
                                    <li>
                                        <div class="col-1">2017-10-24</div>
                                        <div class="col-2">1元</div>
                                        <div class="col-3">分享视频收益</div>
                                    </li>
                                    <li>
                                        <div class="col-1">2017-10-24</div>
                                        <div class="col-2">3元</div>
                                        <div class="col-3">精品试题购买</div>
                                    </li>
                                    <li>
                                        <div class="col-1">2017-10-24</div>
                                        <div class="col-2">2元</div>
                                        <div class="col-3">账户充值</div>
                                    </li>
                                    <li>
                                        <div class="col-1">2017-10-24</div>
                                        <div class="col-2">1元</div>
                                        <div class="col-3">分享视频收益</div>
                                    </li>
                                    <li>
                                        <div class="col-1">2017-10-24</div>
                                        <div class="col-2">50元</div>
                                        <div class="col-3">订制视频</div>
                                    </li>
                                    <li>
                                        <div class="col-1">2017-10-24</div>
                                        <div class="col-2">20元</div>
                                        <div class="col-3">精品试题购买</div>
                                    </li>
                                </div>
                                <!--分享效益-->
                                <div class="disnone" id="incomemoney">
                                    <li>
                                        <div class="col-1">2017-10-24</div>
                                        <div class="col-2">0.2元</div>
                                        <div class="col-3">精品试题购买</div>
                                    </li>
                                    <li>
                                        <div class="col-1">2017-10-24</div>
                                        <div class="col-2">200元</div>
                                        <div class="col-3">账户充值</div>
                                    </li>
                                    <li>
                                        <div class="col-1">2017-10-24</div>
                                        <div class="col-2">20元</div>
                                        <div class="col-3">分享视频收益</div>
                                    </li>
                                    <li>
                                        <div class="col-1">2017-10-24</div>
                                        <div class="col-2">50元</div>
                                        <div class="col-3">订制视频</div>
                                    </li>
                                    <li>
                                        <div class="col-1">2017-10-24</div>
                                        <div class="col-2">20元</div>
                                        <div class="col-3">精品试题购买</div>
                                    </li>
                                </div>
                            </div>
                        </div>
                    </ul>
                </div>
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
<!--时间插件-->
<script src="/www/slcms/Public/admin/js/date-time/bootstrap-datepicker.js"></script>
<script src="/www/slcms/Public/home/js/ajaxfileupload.js"></script>
<!--提示框-->
<!--<script src="/www/slcms/Public/admin/js/bootbox.js"></script>-->

<script type="text/javascript">
    //时间插件
    $(function () {

        loadRegion('#city', <?php echo ($user['province']); ?>,'',<?php echo ($user['city']); ?>);
        loadRegion('#town', <?php echo ($user['city']); ?>,'',<?php echo ($user['town']); ?>);

        jQuery(function ($) {
            $('#birthday').datepicker({
                format: 'yyyy-mm-dd',
                weekStart: 1,
                autoclose: true,
                todayBtn: 'linked',
                language: 'cn'
            });
        });
    });

</script>
<script type="text/javascript">
    //     省市县联动

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
    $('#province').change(function(){
        var val = $('#province option:selected').val();
        loadRegion('#city',val);
    })
    $('#city').change(function(){
        var val = $('#city option:selected').val();
        loadRegion('#town',val);
    })
</script>
<script type="text/javascript">
    //   上传头像预览
    function preview(file)
    {
        var prevDiv = document.getElementById('preview');
        if (file.files && file.files[0])
        {
            var reader = new FileReader();
            reader.onload = function(evt){
                prevDiv.innerHTML = '<img style="width: 220px;height: 220px;" src="' + evt.target.result + '" />';
            }
            reader.readAsDataURL(file.files[0]);
        }
        else
        {
            prevDiv.innerHTML = '<div style="width: 220px;height: 220px;filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(sizingMethod=scale,src=\'' + file.value + '\'"></div>';
        }
        console.log(file.files[0]);
    }
</script>
<script type="text/javascript">
    //ajax表单提交数据
    $("#getsubmit").click(function(){
        var cont = $("form").serialize();
        $.ajax({
            url:"<?php echo U('Usercenter/addtwo');?>",
            type:'post',
            dataType:'json',
            data:cont,
//            success:function(data){
//                var str = data.username + data.age + data.job;
//                $("#result").html(str);
//            }
        });
    });

</script>
<script type="text/javascript">
    $(function () {
        $("#imgbutton").click(function () {
            ajaxFileUpload();
        })
    })
    function ajaxFileUpload() {
        $.ajaxFileUpload
        (
            {
                url: "<?php echo U('Usercenter/upload');?>", //用于文件上传的服务器端请求地址
                secureuri: false, //是否需要安全协议，一般设置为false
                fileElementId: 'fileUpload', //文件上传域的ID
                dataType: 'json', //返回值类型 一般设置为json
                success: function (data, status)  //服务器成功响应处理函数
                {
                    $("#imgReLook").attr("src", data);

                }

            }
        )
        return false;
    }

</script>


<script type="text/javascript">
   
   //修改信息表单提交
     $(function(){
         
         $("#getsubmit").click(function(){
           
            var username = $("#username").val(),
                truename = $("#truename").val(),
                mobile = $("#mobile").val(),
                birthday = $("#birthday").val(),
                email = $("#email").val(),
                province = $("#province").val(),
                city = $("#city").val(),
                town = $("#town").val(),
                sex = $("#sex").val();
       
           if (username == '') {
             alert('用户昵称不能为空');
                return false;
            }
            
            if (truename == '') {
             alert('真实姓名不能为空');
                return false;
            }
            if (mobile == '') {
             alert('联系电话不能为空');
                return false;
            }
            if (email == '') {
             alert('邮箱不能为空');
                return false;
            }
            
             $("#form").submit();
        });
    });
</script>
<!--账户收支菜单-->
<script>
    function incomeAll(t){
      $(t).addClass("active").siblings("a").removeClass("active");
      $("#incomeall").show().siblings(".disnone").hide();
        $("#datum .details .rightbox .overflow").css("top",0);
    };
    function incomeIn(t){
        $(t).addClass("active").siblings("a").removeClass("active");
        $("#incomein").show().siblings(".disnone").hide();
        $("#datum .details .rightbox .overflow").css("top",0);
    };
    function incomeCome(t){
        $(t).addClass("active").siblings("a").removeClass("active");
        $("#incomecome").show().siblings(".disnone").hide();
        $("#datum .details .rightbox .overflow").css("top",0);
    };
    function incomeMoney(t){
        $(t).addClass("active").siblings("a").removeClass("active");
        $("#incomemoney").show().siblings(".disnone").hide();
        $("#datum .details .rightbox .overflow").css("top",0);
    };
</script>
<!--账户收支上下页-->
<script>
    function nextIncome(){
        if($("#incomeall").css("display")=="block"){
            var $c = $("#incomeall");
        }else if($("#incomein").css("display")=="block"){
            var $c = $("#incomein");
        }else if($("#incomecome").css("display")=="block"){
            var $c = $("#incomecome");
        }else if($("#incomemoney").css("display")=="block"){
            var $c = $("#incomemoney");
        }
        var ch = $c.height();
        var oh = $c.parents(".overflowbox").height();
        var t = parseFloat($c.parents(".overflow").css("top"));
        t = t<-(ch-(oh+10))?-(ch-oh):t-oh;
        $c.parents(".overflow").css("top",t);
    };
    function prevIncome(){
        if($("#incomeall").css("display")=="block"){
            var $c = $("#incomeall");
        }else if($("#incomein").css("display")=="block"){
            var $c = $("#incomein");
        }else if($("#incomecome").css("display")=="block"){
            var $c = $("#incomecome");
        }else if($("#incomemoney").css("display")=="block"){
            var $c = $("#incomemoney");
        }
        var ch = $c.height();
        var oh = $c.parents(".overflowbox").height();
        var t = parseFloat($c.parents(".overflow").css("top"));
        t = t<0-2?t+oh:0;
        $c.parents(".overflow").css("top",t);
    };
</script>
</body>
</html>