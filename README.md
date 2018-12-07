# plugin-claim
이 어플리케이션은 Xpressengine3(이하 XE3)의 플러그인 입니다.

이 플러그인은 XE3에서 신고 기능을 제공합니다.

[![License](http://img.shields.io/badge/license-GNU%20LGPL-brightgreen.svg)]

# Installation
### Console
```
$ php artisan plugin:install claim
```

### Web install
- 관리자 > 플러그인 & 업데이트 > 플러그인 목록 내에 새 플러그인 설치 버튼 클릭
- `claim` 검색 후 설치하기

### Ftp upload
- 다음의 페이지에서 다운로드
    * https://store.xpressengine.io/plugins/claim
    * https://github.com/xpressengine/plugin-claim/releases
- 프로젝트의 `plugins` 디렉토리 아래 `claim` 디렉토리명으로 압축해제
- `claim` 디렉토리 이동 후 `composer dump` 명령 실행

# Usage
XE3에서 제공하는 게시판, 댓글에서 특별한 설정 없이 신고 기능을 제공합니다.

> 게시물 내용, 댓글의 토글 버튼을 클릭 > `신고`버튼을 통해서 신고합니다.
>
> `관리자 > 컨텐츠 > 신고` 메뉴를 통해서 신고 내역을 확인할 수 있습니다.

## License
이 플러그인은 LGPL라이선스 하에 있습니다. <https://opensource.org/licenses/LGPL-2.1>
