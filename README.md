# short-url
一个简单的短网址服务，使用PHP实现。

## 演示
[在线演示](https://go.caoj.org/)

## 运行截图
![](https://i.imgur.com/HJAByMz.png)

![](https://i.imgur.com/oY2HCP3.png)

![](https://i.imgur.com/l8ajtCT.png)

![](https://i.imgur.com/oIuCWTH.png)


## 安装
首次使用时，请访问 `/install` 子目录，运行安装程序。

如果你使用的是Apache服务器，由于已经自带了`.htaccess`文件，因此你无需进行额外的配置。
如果你使用的是Nginx服务器，则需要添加如下重定向配置：

```nginx
location / {
  if (!-e $request_filename) {
    rewrite "^/(.*)$" "/api/go.php?id=$1" last;
  }
}
```