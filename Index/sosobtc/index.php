<?php
// GitHub下载方式
require_once __DIR__ . '/../../autoloader.php';
use phpspider\core\phpspider;
use phpspider\core\db;

/* Do NOT delete this comment */
/* 不要删除这段注释 */

$configs = array(
    'name' => 'sosobtc',//定义当前爬虫名称
    'log_show' => false,//是否显示日志
    'tasknum' => 1,
    //要抓取网站的域名
    'domains' => array(
        'www.sosobtc.com',
        'sosobtc.com'
    ),
    //定义爬虫的入口链接, 爬虫从这些链接开始爬取,同时这些链接也是监控爬虫所要监控的链接
    'scan_urls' => array(
        'https://www.sosobtc.com/news/all'
    ),
    //定义列表页url的规则
    'list_url_regexes' => array(
        "https://www.sosobtc.com/api/data/more?cat=all&last=\d+"
    ),
    //定义内容页url的规则
    'content_url_regexes' => array(
        "https://www.sosobtc.com/article/\d+.html",
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
            'selector' => "//header[contains(@class,'article-header')]/h2",
            'required' => true,
        ),
        array(
            'name' => "title_image",
            'selector' => "//section[contains(@class,'article-main')]//img[1]",
            'required' => true,
        ),
        array(
            'name' => "author",
            'selector' => "//div[contains(@class,'info-source')]",
            'required' => true,
        ),
        array(
            'name' => "content",
            'selector' => "//section[contains(@class,'article-main')]",
            'required' => true,
        ),
        array(
            'name' => "new_date",
            'selector' => "//span[contains(@class,'timestamp')]/span",
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
$spider->on_extract_page = function ($page, $data, $configs) {
    foreach ($data as $key => $value) {
        file_put_contents(__DIR__ . "/index.log", $key . "=>" . $value . "\n", FILE_APPEND);
    }
    $data['author'] = str_replace(' ', '', $data['author']);
    $data['source'] = 'sosobtc';
    $data['source_url'] = 'https://www.sosobtc.com/news/all';
//    db::insert("data_source", $data);
};
$spider->start();
