# management_easy(勤怠管理システム)
- アプリ URL</br>
  http://18.180.240.234
  
- 当月分の勤怠を管理出来るアプリ。</br>
  <使い方></br>
  1.出社後、出勤ボタンを押下(押下時にSlackに出勤連絡が通知される)</br>
  2.退社時に退勤ボタンを押下(ボタンを押し忘れた時は次の日に自動で押下してくれる)</br>
  3.遅刻 or その他の連絡事項があれば連絡(Slackに連絡内容が連携される)</br>
  4.月末に管理者へ勤怠を送信</br>
  5.送信された勤怠を承認 or 差し戻しを選択する(管理者用)</br>
  
 - ポートフォリオ用Slackのご案内</br>
 Slackの連携部分を確認したい方は下記のURLから入ってください。</br>
 https://join.slack.com/t/pf-wll1848/shared_invite/zt-kxigp2c1-ewxILvTN5q_UXkEVcCRMLw
 
  
## 作成目的
　①「勤務時間手打ち入力＋出社報告メールを送信する」作業に5分かかっている。</br>
　②手入力する箇所が多くて、始めて使う時は分かりにくい。</br>
　上記の問題を解決する為、勤怠管理が簡略化(1クリック操作)出来て、分かりやすいアプリを作成しました。</br>

## technology(使用技術)
- PHP 7.2.4
- Laravel 6.2.4
- javaScript
- docker
- AWS (VPC、EC2)

## Features(機能)
### 1.ログイン機能
  - 1-1.一般ユーザー用ログイン</br>
  - 1-2.管理者用ログイン</br>
### 2.勤怠管理機能
  - 2-1.勤怠登録(Slack連携)</br>
  - 2-2.勤怠編集</br>
  - 2-3.勤怠一覧表示</br>
  - 2-4.勤怠送信機能</br>
  - 2-5.勤怠情報</br>
  - 2-6.システム設定</br>
### 3.連絡事項機能
  - 3-1.連絡一覧機能</br>
  - 3-2.連絡事項登録(Slack連携)</br>
  - 3-3.連絡事項一覧表示</br>
  - 3-4.連絡事項編集、削除</br>
### 4.管理者機能
  - 4-1.申請者の一覧表示
  - 4-2.申請者の勤怠詳細
  - 4-3.勤怠の承認
  - 4-4.勤怠の差し戻し
  - 4-5.社員の一覧表示

## 1.ログイン機能
  - 1-1.一般ユーザー用ログイン
  - 1-2.管理者用ログイン</br>
<説明></br>
  簡易ログインが出来るように実装
<div align="center">
  <img width=95% alt="47bd7b89f243fa22aa11e003739b69cf" src="https://user-images.githubusercontent.com/58096254/106054192-03338f80-612f-11eb-8bea-7c5dc352288c.png">
</div>

## 2.勤怠管理機能 
- 2-1.勤怠登録(slack連携)

<div align="center">
  <img width=95% alt="47bd7b89f243fa22aa11e003739b69cf" src="https://user-images.githubusercontent.com/58096254/105898295-99e34c00-605c-11eb-87f4-c63c4aab87a3.gif">
</div>

- 2-3.勤怠一覧表示
- 2-4.勤怠送信機能</br>
<説明></br>
  月末に管理者へ送信
<div align="center">
    <img src="https://user-images.githubusercontent.com/58096254/106053897-aa63f700-612e-11eb-9188-0a3882e38cb8.png" width=95%>
</div>

## 3.連絡事項機能
- 3-1.連絡一覧機能</br>
<説明></br>
  当日日付のみ表示
<div align="center">
  <img width=95% alt="cd5b40c18d852124741edb8ea0847334" src="https://user-images.githubusercontent.com/58096254/106189002-0d1ac880-61eb-11eb-8477-6f838895dc53.png">
</div>

- 3-2.連絡事項登録(Slack連携)
<div align="center">
  <img width=95% alt="47bd7b89f243fa22aa11e003739b69cf" src="https://user-images.githubusercontent.com/58096254/105904470-84722000-6064-11eb-9e05-30f32b8e47ee.gif">
</div>


### 4.管理者機能
  - 4-1.申請者の一覧表示
<div align="center">
    <img src="https://user-images.githubusercontent.com/58096254/106339043-c3a8a700-62d8-11eb-880c-75ba847fd8ba.png" width=95%>
</div>
  
  - 4-2.申請者の勤怠詳細
  - 4-3.勤怠の承認
  - 4-4.勤怠の差し戻し
<div align="center">
    <img src="https://user-images.githubusercontent.com/58096254/106054806-e186d800-612f-11eb-8308-3ff8526e98f9.png" width=95%>
</div>

- <勤怠申請時></br>
  申請中 or　差し戻しされた場合、勤怠が更新出来ないように対応
<div align="center">
  <img src="https://user-images.githubusercontent.com/58096254/106190025-69cab300-61ec-11eb-97f1-80afefdd5cbd.png" width=95%>
  <img src="https://user-images.githubusercontent.com/58096254/106190828-85828900-61ed-11eb-8830-aa7409f6bb6b.png" width=95%>
</div>
