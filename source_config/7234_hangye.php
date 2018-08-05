<?php
    return  [
        'name' => '链向财经',//定义当前爬虫名称
        'url' => 'www.7234.cn',
        //要抓取网站的域名
        'domains' => [
            'www.7234.cn',
            '7234.cn'
        ],
        //定义爬虫的入口链接, 爬虫从这些链接开始爬取,同时这些链接也是监控爬虫所要监控的链接
        'scan_urls' => [
            'https://www.7234.cn'
        ],
        //定义列表页url的规则
        'list_url_regexes' => [
            "https://www.7234.cn/fetch_articles/hangye?page=\d+"
        ],
        //定义内容页url的规则
        'content_url_regexes' => [
            "https://www.7234.cn/hangye/\d",
        ],
        //定义内容页的抽取规则
        //规则由一个个field组成, 一个field代表一个数据抽取项
        'fields' => [
            [
                'name' => "title",
                'selector' => "//div[@class,'article-inner']//h1",
                'required' => true,
            ],
            [
                'name' => "title_image",
                'selector' => "//div[@class,'a-content']/p[1]",
                'required' => true,
            ],
            [
                'name' => "author",
                'selector' => "//div[@class,'a-info']/span[2]",
                'required' => true,
            ],
            [
                'name' => "content",
                'selector' => "//div[@class,'a-content']",
                'required' => true,
            ],
            [
                'name' => "new_date",
                'selector' => "//div[@class,'a-info']/span[3]",
                'required' => true,
            ],
        ],
    ];