# 青春图床
一款超高性能的图床程序

# 主要技术
1. 基于swoole的高性能框架 hyperf
2. 分布式搜索引擎 Elasticsearch (这个还在研究中)
3. MySQL数据库
4. 渐进式前端框架 Vue.js
5. redis做消费者队列
6. 最新的php特性

# 运行环境
php版本：>= 7.4.0

其他依赖用composer安装会体现出来

# 运行步骤
1. `git clone https://github.com/lantongxue/young-pictures.git`
2. `cd young-pictures`
3. `composer install -vvv` 安装php依赖
4. `cd frontend` 进入前端目录
5. `npm install` 安装vue相关的依赖
6. `npm run build` 生成dist文件夹，里面包含编译好的vue
7. `cd ../` 返回到young-pictures目录下
8. `cp .env.example .env` 并修改`.env`里面的配置
9. `php bin/hyperf.php migrate` 迁移数据库
10. `php bin/hyperf.php start` 启动主程序

正常启动会监听9501端口，之后用nginx做一个反代就行了

贴一下我的nginx配置
```nginx
server {
    # 监听端口
    listen 80;
    # 绑定的域名，填写您的域名
    server_name yp.3yi.ink;

    location / {
        root /var/www/young-pictures/frontend/dist/;
        index index.html;
    }

    location ^~ /s/ {
        # 将客户端的 Host 和 IP 信息一并转发到对应节点
        proxy_set_header Host $http_host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;

        # 转发Cookie，设置 SameSite
        proxy_cookie_path / "/; secure; HttpOnly; SameSite=strict";

        # 执行代理访问真实服务器
        proxy_pass http://127.0.0.1:9501;
    }
    location /browse {
        # 将客户端的 Host 和 IP 信息一并转发到对应节点
        proxy_set_header Host $http_host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;

        # 转发Cookie，设置 SameSite
        proxy_cookie_path / "/; secure; HttpOnly; SameSite=strict";

        # 执行代理访问真实服务器
        proxy_pass http://127.0.0.1:9501;
    }
    location /upload {
        # 将客户端的 Host 和 IP 信息一并转发到对应节点
        proxy_set_header Host $http_host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;

        # 转发Cookie，设置 SameSite
        proxy_cookie_path / "/; secure; HttpOnly; SameSite=strict";

        # 执行代理访问真实服务器
        proxy_pass http://127.0.0.1:9501;
    }
    location /test {
        # 将客户端的 Host 和 IP 信息一并转发到对应节点
        proxy_set_header Host $http_host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;

        # 转发Cookie，设置 SameSite
        proxy_cookie_path / "/; secure; HttpOnly; SameSite=strict";

        # 执行代理访问真实服务器
        proxy_pass http://127.0.0.1:9501;
    }
}
```
