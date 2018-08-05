<?php
// GitHub下载方式
require_once __DIR__ . '/../../autoloader.php';
use phpspider\core\phpspider;
use phpspider\core\db;
/* Do NOT delete this comment */
/* 不要删除这段注释 */

$configs = array(
    'name' => '链世界',//定义当前爬虫名称
    'url' => 'www.7234.cn',
    'log_show' => false,//是否显示日志
    'tasknum' => 1,
    //要抓取网站的域名
    'domains' => array(
        'www.7234.cn',
        '7234.cn'
    ),
    //定义爬虫的入口链接, 爬虫从这些链接开始爬取,同时这些链接也是监控爬虫所要监控的链接
    'scan_urls' => array(
        'https://www.7234.cn/hangye/'
    ),
    //定义列表页url的规则
    'list_url_regexes' => array(
        "https://www.7234.cn/hangye/?page=\d"
    ),
    //定义内容页url的规则
    'content_url_regexes' => array(
        "https://www.7234.cn/hangye/\d",
    ),
    //爬虫爬取每个网页失败后尝试次数
    'max_try' => 5,
    //数据库配置
    'db_config' => array(
        'host' => '127.0.0.1',
        'port' => 3306,
        'user' => 'root',
        'pass' => 'root',
        'name' => 'wordpress',
    ),
    //定义内容页的抽取规则
    //规则由一个个field组成, 一个field代表一个数据抽取项
    'fields' => array(
        array(
            'name' => "title",
            'selector' => "//div[contains(@class,'article-inner')]//h1[1]",
            'required' => true,
        ),
        array(
            'name' => "title_image",
            'selector' => "//div[contains(@class,'a-content')]//img",
            'required' => true,
        ),
        array(
            'name' => "author",
            'selector' => "//div[contains(@class,'a-info')]/span[2]",
            'required' => true,
        ),
        array(
            'name' => "content",
            'selector' => "//div[contains(@class,'a-content')]",
            'required' => true,
        ),
        array(
            'name' => "new_date",
            'selector' => "//div[contains(@class,'a-info')]/span[3]",
            'required' => true,
        ),
    ),
);

$spider = new phpspider($configs);
//链接数据库
$spider->on_start = function ($phpspider) {
    $db_config = $phpspider->get_config("db_config");
    // 数据库连接
    db::set_connect('default', $db_config);
    db::init_mysql();
};
//更新数据
$spider->on_extract_page = function ($page, $data,$configs) {
    foreach ($data as $key => $value) {
        file_put_contents(__DIR__ . "/index.log", $key . "=>" . $value . "\n", FILE_APPEND);

        $data['author'] = str_replace(' ', '', $data['author']);
        $data['source'] = '链世界-行业';
        $data['source_url'] = 'https://www.7234.cn/hangye/';
    }
    db::insert("data_source", $data);
};
$spider->start();
