# アプリケーション名
**Atte**

## 作成した目的
初級模擬案件の課題として作成いたしました。

## アプリケーションURL
http://localhost/login
こちらにアクセスすると、ログインページが表示されます。  
新規会員登録をクリックしていただき、会員登録を行ってください。  
メール認証のテストサーバーとして、mailtrapというアプリを使用しております  
[Mailtrap](https://mailtrap.io/)こちらからアクセスしていただき、認証メールを受信してください。  
アプリのアカウント作成をしていただき、envファイルでのSMTPサーバー設定等につきましては、下記の環境設定を参照ください。


## 機能一覧
会員登録  
ログイン機能  
メール認証機能  
ログアウト  
勤怠時間の打刻機能  
休憩時間の打刻機能  
日付別、勤務時間の一覧  
従業員一覧  
従業員別、勤務時間の一覧  

## 使用技術
laravel : 8.*  
nginx : 1.21.1  
PHP : 8.2  
mysql : 8.0.26  
Docker : 24.0.6  

## テーブル設計


## ER図


## 環境構築
以下の手順に従って、ローカル環境でこのアプリケーションをセットアップしてください。

### 1.リポジトリのクローン
$ git clone git@github.com:noda-mari/20240215_nodamari_atte.git

### 2. Dockerコンテナの起動とビルド
$ docker-compose up -d --build

### 3. Composer パッケージのインストール
$ docker-compose exec php bash &emsp;&emsp; PHPコンテナ内にログイン

$ composer install

### 4. 環境ファイルの設定
$ cp .env.example .env              &emsp;&emsp; `.env.example` ファイルを `.env` という名前でコピー

$ exit

### 5. 環境ファイルの設定

VScode等で、envファイル内の環境設定の変数を変更する
envファイルの11行目を以下のように変更してください。

DB_CONNECTION=mysql  
DB_HOST=mysql  
DB_PORT=3306  
DB_DATABASE=laravel_db  
DB_USERNAME=laravel_user  
DB_PASSWORD=laravel_pass  

### 6. アプリケーションキーの生成
$ php artisan key:generate

### 7. データベースのマイグレーション
$ php artisan migrate

### 8. ダミーデータを入れる
$ php artisan db:seed

## メール認証の確認に使用するアプリの設定方法

[Mailtrap](https://mailtrap.io/)
無料で利用するのに、クレジットカードの登録などは不要です。
アカウント作成後、すぐに利用することができます。

アカウントは、Googleアカウントなどで連携するか、メールアドレスでアカウントを作成できます。

サインイン後、受信トレイの設定が開かれます。

プルダウンから「Laravel 7.x and 8.x」を選択します。

Laravelの.envファイルにそのまま追記できる形式で、サーバー・ユーザー・パスワードなどが記述されています。
右上のCopyボタンをクリックし、Laravelプロジェクトの.envファイルに貼り付けて、サーバー設定を変更してください。

.env 31行目から36行目

MAIL_MAILER=smtp    &emsp;&emsp;&emsp;こちらはデフォルトです。  
MAIL_HOST=mailhog  
MAIL_PORT=1025  
MAIL_USERNAME=null  
MAIL_PASSWORD=null  
MAIL_ENCRYPTION=null  

.env 36行目も以下に設定お願いします。

MAIL_FROM_ADDRESS=test@gmail.com

上記の設定をしない場合、「Cannot send message without a sender address」というエラーがLaravel上で発生します。

以上で、新規会員登録時[Mailtrap](https://mailtrap.io/)のMy Inboxに認証メールが届きますので、届いたメールを開き
Verify Email Addressをクリックして認証完了です。

## 各種機能のテスト用アカウント情報

name : 山田花子  
email : hanako@test.com  
password : hanako0000  

こちらを使用して、ログインお願いいたします。











