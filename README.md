## Google And Baidu Translate

### 包含Google翻译和国内百度翻译

### 使用方法

~~~
#谷歌翻译
Translate::google()->source('en')->target('zh')->translate('Hello,World');

#百度翻译
$config = [
    'appid'=>'',
    'key'=>''
];
Translate::baidu($config)->source('en')->target('zh')->translate('Hello,World');
~~~

## Installation

```shell
composer require cwrg/translates
```
