<?php
return array (
  'sitename' => '小说聚合程序234',
  'siteurl' => 'http://www.ptcms123.com',
  'beian' => '京ICP备111111号',
  'email' => 'admin#ptcms.com',
  'qq' => '10000',
  'admin_pagesie' => '20',
  'allow_module' => 'admin,index,friendlink,ad,page,attachment,rule,novelsearch,cron,user,sitemap,build,install,author',
  'config_group' => 
  array (
    0 => '不分组',
    1 => '基本',
    2 => '数据库',
    3 => '显示',
    4 => '功能',
    5 => '开放登录',
    6 => '自动采集',
    -1 => 'TKD',
    -2 => 'URL',
    -3 => '采集',
  ),
  'tpl_theme' => 'default',
  'addir' => 'ptcms',
  'runinfo' => 'Processed in {time}(s), Memory: {mem}MB, Sqls: {sql}, cacheread: {cacheread}, cachewrite: {cachewrite}, net:{net}.',
  'mysql_driver' => 'pdo',
  'mysql_prefix' => 'ptcms_',
  'mysql_master_host' => 'localhost',
  'mysql_master_port' => '3306',
  'mysql_master_name' => 'www_ptso_com',
  'mysql_master_user' => 'root',
  'mysql_master_pwd' => 'root',
  'app_status' => '1',
  'app_closemsg' => '网站升级中，请稍后访问！',
  'default_module' => 'novelsearch',
  'plugin' => 
  array (
    'template_compile_end' => 
    array (
      0 => 'cleartitle',
    ),
  ),
  'upload_path' => 'uploads',
  'water_type' => '0',
  'water_image' => '/uploads/201410/16/81c7c4c938a730efd011a68794ad2e51.png',
  'water_text' => '图片上传于www.ptcms.com',
  'water_font' => 'ptcms.otf',
  'water_position' => '9',
  'water_alpha' => '60',
  'water_fontsize' => '20',
  'water_color' => '#666666',
  'logo' => '/uploads/201410/16/f7786c480bebd49c9248c75c474919b8.png',
  'pagesize_chapterlist' => '50',
  'pagesize_categorylist' => '15',
  'pagesize_toplist' => '15',
  'pagesize_search' => '20',
  'cache_driver' => 'memcache',
  'visit_day' => '20160927',
  'visit_num' => '1',
  'visit_update' => '7',
  'pagesign' => 
  array (
    'novelsearch.index.index' => 'index',
    'novelsearch.list.top' => 'top',
    'novelsearch.list.category' => 'category',
    'novelsearch.novel.index' => 'info',
    'novelsearch.novel.author' => 'author',
    'novelsearch.novel.dir' => 'dir',
    'novelsearch.chapter.list' => 'chapterlist',
    'novelsearch.chapter.frame' => 'frame',
    'novelsearch.chapter.go' => 'go',
    'novelsearch.chapter.green' => 'green',
    'novelsearch.chapter.read' => 'read',
    'novelsearch.search.result' => 'search',
    'page.index.detail' => 'page',
    'sitemap.index.index' => 'sitemapindex',
    'sitemap.index.info' => 'sitemapinfo',
    'novelsearch.list.over' => 'over',
    'novelsearch.novel.readend' => 'readend',
    'novelsearch.index.top' => 'topindex',
    'novelsearch.index.category' => 'categoryindex',
    'novelsearch.search.index' => 'searchindex',
  ),
  'url_rules' => 
  array (
    'novelsearch.index.index' => '/',
    'novelsearch.novel.index' => '/novel/{novelkey}/',
    'novelsearch.chapter.list' => '/novel/chapterlist/{novelkey}[_{sitekey}][_{page}].html',
    'novelsearch.novel.dir' => '/dir/{novelkey}/[{sitekey}/]',
    'novelsearch.novel.author' => '/author/{author}',
    'novelsearch.chapter.read' => '/novel/{novelkey}/read_{chapterid}.html',
    'novelsearch.chapter.frame' => '/novel/{novelkey}/kj_{chapterid}.html',
    'novelsearch.chapter.go' => '/novel/{novelkey}/go_{chapterid}.html',
    'novelsearch.novel.readend' => '/novel/{novelkey}/readend.html',
    'novelsearch.list.over' => '/over/[{page}.html]',
    'novelsearch.index.category' => '/sort/',
    'novelsearch.index.top' => '/top/',
    'novelsearch.search.index' => '/search/',
    'novelsearch.chapter.green' => '/novel/{novelkey}/tc_{chapterid}.html',
    'novelsearch.list.category' => '/sort[/{key}][/{page}].html',
    'novelsearch.list.top' => '/top[/{type}][/{page}].html',
    'page.index.detail' => '/about/{key}.html',
    'novelsearch.search.result' => '/search.html',
    'sitemap.index.index' => '/sitemap/index.xml',
    'sitemap.index.info' => '/sitemap/{page}.xml',
  ),
  'url_router' => 
  array (
    '^novel/([a-zA-Z0-9_\\-]+)(\\?(.*))*$' => 'novelsearch/novel/index?novelkey',
    '^novel/chapterlist/([a-zA-Z0-9_\\-]+)_([a-zA-Z0-9_\\-]+)_(\\d+).html(\\?(.*))*$' => 'novelsearch/chapter/list?novelkey&sitekey&page',
    '^novel/chapterlist/([a-zA-Z0-9_\\-]+)_(\\d+).html(\\?(.*))*$' => 'novelsearch/chapter/list?novelkey&page',
    '^novel/chapterlist/([a-zA-Z0-9_\\-]+)_([a-zA-Z0-9_\\-]+).html(\\?(.*))*$' => 'novelsearch/chapter/list?novelkey&sitekey',
    '^novel/chapterlist/([a-zA-Z0-9_\\-]+).html(\\?(.*))*$' => 'novelsearch/chapter/list?novelkey',
    '^dir/([a-zA-Z0-9_\\-]+)/([a-zA-Z0-9_\\-]+)(\\?(.*))*$' => 'novelsearch/novel/dir?novelkey&sitekey',
    '^dir/([a-zA-Z0-9_\\-]+)(\\?(.*))*$' => 'novelsearch/novel/dir?novelkey',
    '^author/([^\\..]+?)(\\?(.*))*$' => 'novelsearch/novel/author?author',
    '^novel/([a-zA-Z0-9_\\-]+)/read_(\\d+).html(\\?(.*))*$' => 'novelsearch/chapter/read?novelkey&chapterid',
    '^novel/([a-zA-Z0-9_\\-]+)/kj_(\\d+).html(\\?(.*))*$' => 'novelsearch/chapter/frame?novelkey&chapterid',
    '^novel/([a-zA-Z0-9_\\-]+)/go_(\\d+).html(\\?(.*))*$' => 'novelsearch/chapter/go?novelkey&chapterid',
    '^novel/([a-zA-Z0-9_\\-]+)/readend.html(\\?(.*))*$' => 'novelsearch/novel/readend?novelkey',
    '^over/(\\d+).html(\\?(.*))*$' => 'novelsearch/list/over?page',
    '^over(\\?(.*))*$' => 'novelsearch/list/over?',
    '^sort(\\?(.*))*$' => 'novelsearch/index/category?',
    '^top(\\?(.*))*$' => 'novelsearch/index/top?',
    '^search(\\?(.*))*$' => 'novelsearch/search/index?',
    '^novel/([a-zA-Z0-9_\\-]+)/tc_(\\d+).html(\\?(.*))*$' => 'novelsearch/chapter/green?novelkey&chapterid',
    '^sort/([a-zA-Z0-9]+)/(\\d+).html(\\?(.*))*$' => 'novelsearch/list/category?key&page',
    '^sort/(\\d+).html(\\?(.*))*$' => 'novelsearch/list/category?page',
    '^sort/([a-zA-Z0-9]+).html(\\?(.*))*$' => 'novelsearch/list/category?key',
    '^sort.html(\\?(.*))*$' => 'novelsearch/list/category?',
    '^top/([a-zA-Z0-9]+)/(\\d+).html(\\?(.*))*$' => 'novelsearch/list/top?type&page',
    '^top/(\\d+).html(\\?(.*))*$' => 'novelsearch/list/top?page',
    '^top/([a-zA-Z0-9]+).html(\\?(.*))*$' => 'novelsearch/list/top?type',
    '^top.html(\\?(.*))*$' => 'novelsearch/list/top?',
    '^about/([a-zA-Z0-9]+).html(\\?(.*))*$' => 'page/index/detail?key',
    '^search.html(\\?(.*))*$' => 'novelsearch/search/result?',
    '^sitemap/index.xml(\\?(.*))*$' => 'sitemap/index/index?',
    '^sitemap/(\\d+).xml(\\?(.*))*$' => 'sitemap/index/info?page',
  ),
  'collect_cover_check' => '1',
  'collect_category_default' => '14',
  'collect_category_rule' => '1|玄幻=玄幻|东方|异界|远古|神话|异世|上古|王朝|争霸|变身情缘|异世争霸|异术超能
2|奇幻=奇幻|西方|领主贵族|亡灵骷髅|异类兽族|魔法校园|吸血传奇
3|武侠=武侠|异侠|国术|武技|国术古武|江湖情仇
4|仙侠=仙侠|古典仙侠|奇幻修真|现代修真|洪荒封神
5|都市=都市|屌丝|生活|商|职场|官场|明星|现实|乡土|宦海|重生|异能|青春|乡村|风云|大亨
6|历史=历史|架空|秦|汉|三国|两晋|隋|唐|五代|十国|宋|元|明|清|民国|传记|穿越|传奇
7|军事=军事|战争|抗战|烽火|军|间谍|暗战|谍战
8|游戏=游戏|网游|电子竞技
9|竞技=竞技|体育|篮球|足球|弈林|棋牌|桌游
10|科幻=科幻|未来|星际|古武机甲|数字生命|科技|时空|进化|变异|末世
11|灵异=灵异|恐怖|惊悚|推理|侦探|悬疑|探险|悬念|神怪|探险|异闻|神秘
12|同人=同人
13|女生=女生|女|言情|言情|豪门|王爷|合租|情缘|恩怨|情仇|校园|情感|总裁
14|其他=其他',
  'httpmethod' => 'curl',
  'timeout' => '10',
  'user_agent' => 'KuaiYanKanShuSpider/4.1 (http://www.kuaiyankanshu.net/about/spider.html)',
  'oauth_power' => '0',
  'oauth_qq_appid' => '',
  'oauth_qq_appsecret' => '',
  'oauth_qq_check' => '',
  'oauth_weibo_check' => '',
  'oauth_weibo_appid' => '',
  'oauth_weibo_appsecret' => '',
  'read_type' => 'green',
  'caption_allcateogry' => '全部',
  'caption_top' => 
  array (
    'postdate' => '入库时间',
    'lastupdate' => '最新更新',
    'dayvisit' => '日点击',
    'weekvisit' => '周点击',
    'monthvisit' => '月点击',
    'allvisit' => '总点击',
    'marknum' => '收藏数',
    'votenum' => '推荐数',
    'downnum' => '下载数',
  ),
  'cron_power' => '0',
  'tkd_index' => 
  array (
    'title' => '{$pt.config.sitename} - 新版快眼看书',
    'keywords' => '{$pt.config.sitename} 搜索阅读网 新版快眼看书',
    'description' => '{$pt.config.sitename}专注于玄幻小说搜索,新版快眼看书,提供最全的小说保持最快的更新,方便大家愉快地阅读玄幻小说',
  ),
  'tkd_category' => 
  array (
    'title' => '{$category.name} - {$pt.config.sitename}',
    'keywords' => '{$category.name},{$pt.config.sitename}',
    'description' => '{$pt.config.sitename}专注于玄幻小说搜索,新版快眼看书,提供最全的小说保持最快的更新,方便大家愉快地阅读玄幻小说',
  ),
  'tkd_top' => 
  array (
    'title' => '{$top.name} - {$pt.config.sitename}',
    'keywords' => '{$top.name},{$pt.config.sitename}',
    'description' => '{$pt.config.sitename}专注于玄幻小说搜索,新版快眼看书,提供最全的小说保持最快的更新,方便大家愉快地阅读玄幻小说',
  ),
  'tkd_info' => 
  array (
    'title' => '{$novel.name}章节目录 - {$novel.name}{$pt.config.sitename}',
    'keywords' => '{$novel.name}最新章节列表,{$novel.name}{$pt.config.sitename}',
    'description' => '{$pt.config.sitename}提供{$category.name}小说《{$novel.name}》最新章节的搜索，更新超级快，无病毒无木马，页面干净清爽，希望大家收藏!',
  ),
  'tkd_dir' => 
  array (
    'title' => '{$novel.name}最新章节目录 - {$novel.name} {$sitename} - {$pt.config.sitename}',
    'keywords' => '{$novel.name}最新章节,{$novel.name} {$sitename}',
    'description' => '{$pt.config.sitename}提供《{$novel.name}》在“{$sitename}”的最新章节目录的索引，更新超级快，无病毒无木马，页面干净清爽，希望大家收藏!',
  ),
  'tkd_author' => 
  array (
    'title' => '{$author.name}作品大全',
    'keywords' => '{$author.name},{$pt.config.sitename}',
    'description' => '{$pt.config.sitename}专注于玄幻小说搜索,新版快眼看书,提供最全的小说保持最快的更新,方便大家愉快地阅读玄幻小说',
  ),
  'tkd_chapterlist' => 
  array (
    'title' => '{$novel.name}最新章节 - {$novel.name} {$sitename} - {$novel.name}快眼看书',
    'keywords' => '{$novel.name}最新章节,{$novel.name} {$sitename} ,{$novel.name}快眼看书',
    'description' => '{$pt.config.sitename}提供《{$novel.name}》最新章节的搜索，更新超级快，无病毒无木马，页面干净清爽，希望大家收藏!',
  ),
  'tkd_frame' => 
  array (
    'title' => '{$novel.name} {$sitename} - {$chapter.name} - {$pt.config.sitename}',
    'keywords' => '{$novel.name} {$sitename},{$chapter.name},{$pt.config.sitename}',
    'description' => '{$pt.config.sitename}提供《{$novel.name}》最新章节的搜索，更新超级快，无病毒无木马，页面干净清爽，希望大家收藏!',
  ),
  'tkd_page' => 
  array (
    'title' => '{$name} - {$pt.config.sitename}',
    'keywords' => '{$name},{$pt.config.sitename}',
    'description' => '{$pt.config.sitename}专注于玄幻小说搜索,新版快眼看书,提供最全的小说保持最快的更新,方便大家愉快地阅读玄幻小说',
  ),
  'tkd_search' => 
  array (
    'title' => '搜索“{$searchkey}”的结果',
    'keywords' => '{$searchkey},{$pt.config.sitename}',
    'description' => '{$pt.config.sitename}专注于玄幻小说搜索,新版快眼看书,提供最全的小说保持最快的更新,方便大家愉快地阅读玄幻小说',
  ),
  'tkd_go' => 
  array (
    'title' => '{$chapter.name}',
    'keywords' => '',
    'description' => '',
  ),
  'rewritepower' => '1',
  'db_type' => 'mysql',
  'read_type2' => 'frame',
  'tkd_green' => 
  array (
    'title' => '{$novel.name} {$sitename} - {$chapter.name} - {$pt.config.sitename}',
    'keywords' => '{$novel.name} {$sitename},{$chapter.name},{$pt.config.sitename}',
    'description' => '{$pt.config.sitename}提供《{$novel.name}》最新章节的搜索，更新超级快，无病毒无木马，页面干净清爽，希望大家收藏!',
  ),
  'wap_theme' => 'wap',
  'wap_domain' => 'http://www.ptcms12345.com',
  'wap_type' => '1',
  'tpl_protect' => 'ptcms',
  'tkd_read' => 
  array (
    'title' => ' {$chapter.name} 转码阅读 - {$novel.name} {$sitename} -{$pt.config.sitename}',
    'keywords' => '{$novel.name} {$sitename},{$chapter.name},{$pt.config.sitename}',
    'description' => '{$pt.config.sitename}提供《{$novel.name}》最新章节的搜索，更新超级快，无病毒无木马，页面干净清爽，希望大家收藏!',
  ),
  'resurl' => '',
  'cronurl' => '',
  'collect_cover_save' => '0',
  'greenread_showtype' => '0',
  'chapter_path' => '@/data/',
  'chapter_cache_power' => '1',
  'chapter_show_type' => '0',
  'greenread_errormsg' => '转码失败！请您使用右上换源切换源站阅读或者直接前往源网站进行阅读！',
  'collect_skip_novel' => '',
  'content_replace' => '',
  'collect_over_caption' => '完结|结束|完本|完成|1',
  'pagesize_over' => '15',
  'readend_type' => '0',
  'apiurl' => '',
  'tkd_over' => 
  array (
    'title' => '全本小说 - {$pt.config.sitename}',
    'keywords' => '全本小说,{$pt.config.sitename}',
    'description' => '{$pt.config.sitename}专注于玄幻小说搜索,新版快眼看书,提供最全的小说保持最快的更新,方便大家愉快地阅读玄幻小说',
  ),
  'tkd_readend' => 
  array (
    'title' => '{$novel.name}章节目录 - {$novel.name}{$pt.config.sitename}',
    'keywords' => '{$novel.name}最新章节列表,{$novel.name}{$pt.config.sitename}',
    'description' => '{$pt.config.sitename}提供{$category.name}小说《{$novel.name}》最新章节的搜索，更新超级快，无病毒无木马，页面干净清爽，希望大家收藏!',
  ),
  'pagesize_dir_wap' => '50',
  'pinyin_uc_first' => '0',
  'tkd_categoryindex' => 
  array (
    'title' => '小说分类 - {$pt.config.sitename}',
    'keywords' => '分类,{$pt.config.sitename}',
    'description' => '{$pt.config.sitename}专注于玄幻小说搜索,新版快眼看书,提供最全的小说保持最快的更新,方便大家愉快地阅读玄幻小说',
  ),
  'tkd_topindex' => 
  array (
    'title' => '小说排行 - {$pt.config.sitename}',
    'keywords' => '排行,{$pt.config.sitename}',
    'description' => '{$pt.config.sitename}专注于玄幻小说搜索,新版快眼看书,提供最全的小说保持最快的更新,方便大家愉快地阅读玄幻小说',
  ),
  'tkd_searchindex' => 
  array (
    'title' => '搜索 - {$pt.config.sitename}',
    'keywords' => '搜索,{$pt.config.sitename}',
    'description' => '{$pt.config.sitename}专注于玄幻小说搜索,新版快眼看书,提供最全的小说保持最快的更新,方便大家愉快地阅读玄幻小说',
  ),
  'wap_tc2read' => '1',
  'collect_reorder' => '0',
  'autoget' => '1',
  'autoid' => '1',
  'autonum' => '51,32',
);