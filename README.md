# short-url
一个简单的短网址服务，使用PHP实现。

## 演示
[在线演示](https://go.caoj.org/)

## 安装
首次使用时，请访问 `/install` 子目录，运行安装程序。

之后，你需要进行重定向配置，对于 Nginx，需要添加如下配置：

```nginx
location / {
  if (!-e $request_filename) {
   	rewrite  ^(.*)$  /go.php?id=/$1  last;
   	break;
  }
}
```