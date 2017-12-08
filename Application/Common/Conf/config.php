<?php
/**
 *
 * 功能说明：配置文件。
 *
 **/
return array(
    //网站配置信息
    'URL' => 'http://www.qwadmin.com', //网站根URL
    'COOKIE_SALT' => '123456', //设置cookie加密密钥
    //备份配置
    'DB_PATH_NAME' => 'db',        //备份目录名称,主要是为了创建备份目录
    'DB_PATH' => './db/',     //数据库备份路径必须以 / 结尾；
    'DB_PART' => '20971520',  //该值用于限制压缩后的分卷最大长度。单位：B；建议设置20M
    'DB_COMPRESS' => '1',         //压缩备份文件需要PHP环境支持gzopen,gzwrite函数        0:不压缩 1:启用压缩
    'DB_LEVEL' => '9',         //压缩级别   1:普通   4:一般   9:最高
    //扩展配置文件
    'LOAD_EXT_CONFIG' => 'db',
    'TMPL_TEMPLATE_SUFFIX'=>'.html',//更改模板文件后缀名
    'TMPL_CACHE_ON' => true,//禁止模板编译缓存 
    'HTML_CACHE_ON' => true,//禁止静态缓存 
);