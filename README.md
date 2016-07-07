# Rich Panel
관리자에 panel 을 통해 queue 에 실행할 명령어를 입력하도록 돕습니다.
queue 는 rich_panel 드라이버를 추가해서 database 로 동작 되도록 했습니다.

서버에 접속해서 명령어 입력하는 일을 어려워하는 일반 사용자를 위해 제작되었습니다.
서버 설정에 따라 파일 권한이나 여러 문제로 인해 명령어 사용에 문제가 발생할 수 있습니다.
Rich Panel 을 사용하는데 발생하는 문제 및 데이터 유실에 대해 책임이 없습니다.




## XE3
XE3 CMS 에 사용되는 플러그인 입니다.
https://xpressengine.io/plugins/detail/rich_panel




## 설치
#### Artisan command
서버에 접속해서 아래 명령어 실행
```
php artisan plugin:install rich_panel
```

#### Git
서버에 접속해서 아래 명령어 실행
```
cd xpressengine
cd plugins
git clone https://github.com/akasima/rich_panel.git
git rich_panel
composer install
```




## Queue Listener 실행
서버에 접속해서 아래 명령어 실행
```
php artisan queue:listen rich_panel --timeout=0 --tries=3 &
```




## 사용
Rich panel 관리자 페이지(`/settings/rich_panel`)로 이동 하고 필요한 명령어 입력하면 관리자에서 artisan command 를 사용할 수 있습니다

> `주의` 명령어 입력 후 confirm 하는 단계가 없습니다.
> 사용 후 발생하는 문제는 사용자에게 있습니다.
> 명령어 입력할 때 잘못된 명령어가 입력되지 않도록 주의 바랍니다.

#### 명령어
|명령어|설명|
|---------------|----------|
| cache:clear-xe | 캐시 삭제 |
| plugin:install plugin_id={pluginName} | 자료실의 플러그인 설치 |
| plugin:uninstall plugin_id={pluginName}| 설치된 플러그인 삭제 |
| translation:import | Core 다국어 업데이트 |
| translation:import name={pluginName} | 플러그인 다국어 업데이트 |
