<?php

use App\Middleware\ModuleMiddleware;
use Clover\Annotation;
use Clover\Classes\Data\ArrayObject;
use Clover\Classes\Data\StringObject;
use Clover\Classes\Database\Driver\PHPDataObject;
use Clover\Classes\Directory\Handler as DirectoryHandler;
use Clover\Classes\File\Handler as FileHandler;
use Clover\Classes\File\Functions;
use Clover\Classes\HTTP\Request;
use Clover\Framework\Component\BaseController;

#[Annotation\Prefix('jyogakuen')]
class JyogakuenController extends BaseController
{
	private $mysqlIp = 'host.docker.internal:3308';

	private $userId = 15408309419429;

	public $dbInstance;

	#[Annotation\Route(method: 'GET', pattern: '/index')]
	public function index(Request $request)
	{
		return $this->render('/App/View/jyogakuen/jyogakuen.html');
	}

	#[Annotation\Route(method: 'GET', pattern: '/iframe')]
	public function iframe(Request $request)
	{
		return $this->render('/App/View/jyogakuen/ifr.htm');
	}

	public function setDbInstance()
	{
		$db = new PHPDataObject();
		$db->setHostName('host.docker.internal:3308');
		$db->setUsername('root');
		$db->setPassword('root!');
		$db->setDatabase('artwhirl');
		$db->connect();

		$this->dbInstance = $db;
	}

	

	#[Annotation\Route(method: 'POST', pattern: '/master/api/game/setting/')]
	public function setting(Request $request)
	{
		$array = array(
			'result' => 'OK',
			'count' => 1,
			'data' => 
		   (object) array(
			  'sada' => 
			 (object) array(
				'USER' => 'apache',
				'HOME' => '/var/www',
				'FCGI_ROLE' => 'RESPONDER',
				'PATH_INFO' => '',
				'PATH_TRANSLATED' => '/data/www/html/harem/master/public',
				'SCRIPT_FILENAME' => '/data/www/html/harem/master/public/index.php',
				'BRANCH' => 'master',
				'BRANCH_TAG' => '',
				'QUERY_STRING' => '_url=/api/game/setting/',
				'REQUEST_METHOD' => 'POST',
				'CONTENT_TYPE' => '',
				'CONTENT_LENGTH' => '0',
				'SCRIPT_NAME' => '/index.php',
				'REQUEST_URI' => '/master/api/game/setting/',
				'DOCUMENT_URI' => '/index.php',
				'DOCUMENT_ROOT' => '/data/www/html/harem/master/public',
				'SERVER_PROTOCOL' => 'HTTP/1.0',
				'GATEWAY_INTERFACE' => 'CGI/1.1',
				'SERVER_SOFTWARE' => 'nginx/1.5.12',
				'REMOTE_ADDR' => '192.168.0.22',
				'REMOTE_PORT' => '57617',
				'SERVER_ADDR' => '192.168.0.26',
				'SERVER_PORT' => '80',
				'SERVER_NAME' => '222.158.207.78',
				'REDIRECT_STATUS' => '200',
				'HTTP_X_FORWARDED_FOR' => '219.100.37.246, 111.171.200.107',
				'HTTP_X_FORWARDED_HOST' => 'koi-server-master.tencross.site',
				'HTTP_X_FORWARDED_SERVER' => 'koi-server-master.tencross.site',
				'HTTP_X_REAL_IP' => '219.100.37.246',
				'HTTP_HOST' => 'backend',
				'HTTP_CONNECTION' => 'close',
				'HTTP_CONTENT_LENGTH' => '0',
				'HTTP_SEC_CH_UA' => '"Google Chrome";v="125", "Chromium";v="125", "Not.A/Brand";v="24"',
				'HTTP_ACCEPT' => '*/*',
				'HTTP_DNT' => '1',
				'HTTP_X_REQUESTED_WITH' => 'XMLHttpRequest',
				'HTTP_SEC_CH_UA_MOBILE' => '?0',
				'HTTP_USER_AGENT' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.0.0 Safari/537.36',
				'HTTP_SEC_CH_UA_PLATFORM' => '"Windows"',
				'HTTP_ORIGIN' => 'http://localhost/jyogakuen',
				'HTTP_SEC_FETCH_SITE' => 'same-origin',
				'HTTP_SEC_FETCH_MODE' => 'cors',
				'HTTP_SEC_FETCH_DEST' => 'empty',
				'HTTP_REFERER' => 'http://localhost/jyogakuen/master/gadget_hh_html5_ssl.html',
				'HTTP_ACCEPT_ENCODING' => 'gzip, deflate, br, zstd',
				'HTTP_ACCEPT_LANGUAGE' => 'ko-KR,ko;q=0.9,en-US;q=0.8,en;q=0.7',
				'PHP_SELF' => '/index.php',
				'REQUEST_TIME_FLOAT' => 1717307392.0368,
				'REQUEST_TIME' => 1717307392,
			 ),
			  'yell_send_limit' => 30,
			  'yell_send_point' => 8,
			  'card_limit_count' => 5,
			  'level_up_point' => 3,
			  'gacha_max_count' => 10,
			  'doc_root' => 'http://localhost/jyogakuen/master/',
			  'api_root' => 'http://localhost/jyogakuen/master/api/',
			  'swf_root' => 'http://localhost/jyogakuen/master/swf/p/',
			  'voice_path' => 'http://localhost/jyogakuen/master/music/p/voice/chara/',
			  'se_path' => 'http://localhost/jyogakuen/master/music/p/se/',
			  'bgm_path' => 'http://localhost/jyogakuen/master/music/p/bgm/',
			  'chara_path' => 'http://localhost/jyogakuen/master/swf/p/chara/m/',
			  'clothes_path' => 'http://localhost/jyogakuen/master/img/p/clothes/m/',
			  'story_bg_path' => 'http://localhost/jyogakuen/master/img/p/story_bg/',
			  'story_item_path' => 'http://localhost/jyogakuen/master/img/p/story_item/',
			  'movie_path' => 'http://localhost/jyogakuen/master/movie/p/',
			  'touch_path' => 'http://localhost/jyogakuen/master/swf/p/touch/',
			  'preventCache' => false,
			  'mainte_flag' => false,
			  'preload_path' => 
			 array (
			   0 => 'http://localhost/jyogakuen/master/swf/p/Font.swf',
			   1 => 'http://localhost/jyogakuen/master/swf/p/Sounds.swf',
			   2 => 'http://localhost/jyogakuen/master/swf/p/Main.swf',
			   3 => 'http://localhost/jyogakuen/master/swf/p/MyPage.swf',
			   4 => 'http://localhost/jyogakuen/master/swf/p/Story.swf',
			   5 => 'http://localhost/jyogakuen/master/swf/p/Top.swf',
			   6 => 'http://localhost/jyogakuen/master/swf/p/LoginBonus.swf',
			 ),
			  'event_flag' => false,
			  'event_type' => NULL,
			  'event_end_date' => NULL,
		   ),
		);

		return $this->responseJson($array);
	}

	#[Annotation\Route(method: 'POST', pattern: '/master/api/announce')]
	public function announce(Request $request)
	{
		$array = array(
			'result' => 'OK',
			'count' => 1,
			'data' => 
		   (object) array(
			  'page' => 
			 (object) array(
				'now' => '1',
				'next' => 2,
				'pre' => 0,
			 ),
			  'type' => 'list',
			  'data' => 
			 array (
			   0 => 
			   (object) array(
				  'announce_id' => '1827',
				  'subject' => '<b>月初めキャンペーン実施のお知らせ</b>',
				  'description' => '<div style="width:800px; height:350px; overflow:auto;">
		 ユーザーの皆様へ<br /><br />
		 
		 いつも「ようこそ！恋ヶ崎女学園へ」をご愛顧くださり誠にありがとうございます。<br /><br />
		 
		 6月1日(土)から6月14日(金)24時まで、月初めキャンペーンを実施させていただきます。<br />
		 月初めキャンペーンでは、毎月1日0時から14日24時までの間に魔法石＋オマケのセットを<br />
		 お得な価格でご購入いただけます。<br /><br />
		 
		 詳細につきましては、ゲーム画面内のインフォメーションおよびショップ画面をご確認ください。<br /><br />
		 
		 今後とも「ようこそ！恋ヶ崎女学園へ」をよろしくお願いいたします。<br /><br />
		 </div>',
				  'new_arrival_start' => '2024-06-01 00:01:00',
				  'new_arrival_end' => '2024-06-14 23:59:00',
				  'enable' => 'Y',
				  'created' => '2024-05-22 11:56:06',
				  'updated' => '2024-05-22 11:56:06',
				  'is_new' => true,
				  'date' => '2024 06/01',
			   ),
			   1 => 
			   (object) array(
				  'announce_id' => '1826',
				  'subject' => '<font color="#0000ff"><b>月刊バトルイベント6月号のお知らせ</b></font>',
				  'description' => '<div style="width:800px; height:350px; overflow:auto;">
		 ユーザーの皆様へ<br /><br />
		 
		 いつも「ようこそ！恋ヶ崎女学園へ」をご愛顧くださり誠にありがとうございます。<br /><br />
		 5月29日(水)から6月10日(月)10時まで、『月刊バトルイベント6月号』を開催させていただきます。<br /><br />
		 
		 【イベントの内容】<br /><br />
		 
		 イベント期間中、『バトル』画面の対戦相手がNPCに変わります。<br />
		 NPCとバトルをするとVP（Victory Point）を入手できます。<br />
		 VPを集めて様々なアイテムや限定SDカードをゲットしよう！<br /><br />
		 
		 ※こちらのバトルイベントには、ストーリーやランキングはありません。<br /><br />
		 
		 【NPCについて】<br /><br />
		 
		 NPCには、入門・初級・中級・上級・超級の5段階があり、その中から対戦相手を自由に選ぶことが<br />
		 できます。<br /><br />
		 
		 NPCは、入門⇒初級⇒中級⇒上級⇒超級の順で強くなっていき、強いNPCほどもらえるVPも多くなります。<br /><br />
		 <font color="#0000ff">
		 ※NPCの体力は回復しませんので、負けてもHPが減った状態から再戦できます。<br /><br />
		 </font>
		 【勝利条件】<br /><br />
		 
		 ■大勝利<br />
		 └1回目のバトルでNPCのHPをゼロにする。<br /><br />
		 
		 ■勝利<br />
		 └2回目以降のバトルでNPCのHPをゼロにする。<br /><br />
		 
		 ■敗北<br />
		 └NPCのHPをゼロにできないまま終了。<br /><br />
		 
		 【NPCごとのVP獲得量】<br />
		 <table border=1>
		  <tr><th style=\'background-color:#666666;color:white;\'>　級　</th>
		  <th style=\'background-color:#666666;color:white;\'>　敗北　</th>
		  <th style=\'background-color:#666666;color:white;\'>　勝利　</th>
		  <th style=\'background-color:#666666;color:white;\'>　大勝利　</th>
		  <tr><td style=\'background-color:#ccffdd\'>超級</td><td>17</td><td>170</td><td>179</td></tr>
		  <tr><td style=\'background-color:#ccffdd\'>上級</td><td>15</td><td>75</td><td>79</td></tr>
		  <tr><td style=\'background-color:#ccffdd\'>中級</td><td>13</td><td>65</td><td>68</td></tr>
		  <tr><td style=\'background-color:#ccffdd\'>初級</td><td>11</td><td>55</td><td>58</td></tr>
		  <tr><td style=\'background-color:#ccffdd\'>入門</td><td>9</td><td>45</td><td>47</td></tr>
		 </table>
		 <br /><br />
		 
		 【6月の限定SDカードはこちら】<br /><br />
		 <font color="#ff00ff"><b>
		 ■[SD]凛（最大4枚）</font></b><br />
		 └最終進化時のレアリティ：ＳＲ<br />
		 └最終攻撃力：6,480・最終防御力：6,156<br />
		 └アビリティ：アンチクールⅠ（アビリティランクC）<br /><br />
		 
		 【VP報酬一覧】<br />
		 
		 <table border=1>
		  <tr><th style=\'background-color:#666666;color:white;\'>　報酬アイテム　</th>
		  <th style=\'background-color:#666666;color:white;\'>　個数　</th>
		  <th style=\'background-color:#666666;color:white;\'>到達VP</th></tr>
		  <tr><td style=\'background-color:#ccffdd\'>BP回復薬（小）</td><td>1</td><td>100</td></tr>
		  <tr><td style=\'background-color:#ccffdd\'>ファンP</td><td>2,000</td><td>200</td></tr>
		  <tr><td style=\'background-color:#ccffdd\'>資金</td><td>2,000</td><td>300</td></tr>
		  <tr><td style=\'background-color:#ccffdd\'>体力回復薬（小）</td><td>1</td><td>400</td></tr>
		  <tr><td style=\'background-color:#ccffdd\'>BP回復薬（小）</td><td>1</td><td>500</td></tr>
		  <tr><td style=\'background-color:#ccffdd\'>ファンP</td><td>3,000</td><td>600</td></tr>
		  <tr><td style=\'background-color:#ccffdd\'>資金</td><td>3,000</td><td>700</td></tr>
		  <tr><td style=\'background-color:#ccffdd\'>BP回復薬（小）</td><td>1</td><td>800</td></tr>
		  <tr><td style=\'background-color:#ccffdd\'>ブルージュエル</td><td>1</td><td>900</td></tr>
		  <tr><td style=\'background-color:#ccffdd\'>BP回復薬（小）</td><td>1</td><td>1,000</td></tr>
		  <tr><td style=\'background-color:#ccffdd\'>ファンP</td><td>4,000</td><td>1,250</td></tr>
		  <tr><td style=\'background-color:#ccffdd\'>資金</td><td>4,000</td><td>1,500</td></tr>
		  <tr><td style=\'background-color:#ccffdd\'>BP回復薬（小）</td><td>1</td><td>1,750</td></tr>
		  <tr><td style=\'background-color:#ccffdd\'>限定SDカード</td><td>1</td><td>2,000</td></tr>
		  <tr><td style=\'background-color:#ccffdd\'>体力回復薬（小）</td><td>1</td><td>2,250</td></tr>
		  <tr><td style=\'background-color:#ccffdd\'>BP回復薬（小）</td><td>2</td><td>2,500</td></tr>
		  <tr><td style=\'background-color:#ccffdd\'>ブルージュエル</td><td>1</td><td>2,750</td></tr>
		  <tr><td style=\'background-color:#ccffdd\'>ファンP</td><td>5,000</td><td>3,000</td></tr>
		  <tr><td style=\'background-color:#ccffdd\'>資金</td><td>5,000</td><td>3,250</td></tr>
		  <tr><td style=\'background-color:#ccffdd\'>BP回復薬（小）</td><td>2</td><td>3,500</td></tr>
		  <tr><td style=\'background-color:#ccffdd\'>レッドジュエル</td><td>1</td><td>3,750</td></tr>
		  <tr><td style=\'background-color:#ccffdd\'>限定SDカード</td><td>1</td><td>4,000</td></tr>
		  <tr><td style=\'background-color:#ccffdd\'>BP回復薬（小）</td><td>2</td><td>4,250</td></tr>
		  <tr><td style=\'background-color:#ccffdd\'>体力回復薬（小）</td><td>1</td><td>4,500</td></tr>
		  <tr><td style=\'background-color:#ccffdd\'>BP回復薬（小）</td><td>2</td><td>4,750</td></tr>
		  <tr><td style=\'background-color:#ccffdd\'>ブルージュエル</td><td>1</td><td>5,000</td></tr>
		  <tr><td style=\'background-color:#ccffdd\'>BP回復薬（小）</td><td>2</td><td>5,250</td></tr>
		  <tr><td style=\'background-color:#ccffdd\'>ブルージュエル</td><td>1</td><td>5,500</td></tr>
		  <tr><td style=\'background-color:#ccffdd\'>レッドジュエル</td><td>1</td><td>5,750</td></tr>
		  <tr><td style=\'background-color:#ccffdd\'>体力回復薬（小）</td><td>1</td><td>6,000</td></tr>
		  <tr><td style=\'background-color:#ccffdd\'>BP回復薬（小）</td><td>2</td><td>6,250</td></tr>
		  <tr><td style=\'background-color:#ccffdd\'>ブルージュエル</td><td>1</td><td>6,500</td></tr>
		  <tr><td style=\'background-color:#ccffdd\'>BP回復薬（小）</td><td>3</td><td>6,750</td></tr>
		  <tr><td style=\'background-color:#ccffdd\'>限定SDカード</td><td>1</td><td>7,000</td></tr>
		  <tr><td style=\'background-color:#ccffdd\'>体力回復薬（小）</td><td>1</td><td>7,250</td></tr>
		  <tr><td style=\'background-color:#ccffdd\'>BP回復薬（小）</td><td>3</td><td>7,500</td></tr>
		  <tr><td style=\'background-color:#ccffdd\'>レッドジュエル</td><td>1</td><td>7,750</td></tr>
		  <tr><td style=\'background-color:#ccffdd\'>スペシャルジュエル</td><td>1</td><td>8,000</td></tr>
		  <tr><td style=\'background-color:#ccffdd\'>BP回復薬（小）</td><td>3</td><td>8,250</td></tr>
		  <tr><td style=\'background-color:#ccffdd\'>体力回復薬（小）</td><td>1</td><td>8,500</td></tr>
		  <tr><td style=\'background-color:#ccffdd\'>BP回復薬（小）</td><td>3</td><td>8,750</td></tr>
		  <tr><td style=\'background-color:#ccffdd\'>ブルージュエル</td><td>1</td><td>9,000</td></tr>
		  <tr><td style=\'background-color:#ccffdd\'>レッドジュエル</td><td>1</td><td>9,250</td></tr>
		  <tr><td style=\'background-color:#ccffdd\'>体力回復薬（小）</td><td>1</td><td>9,500</td></tr>
		  <tr><td style=\'background-color:#ccffdd\'>BP回復薬（小）</td><td>3</td><td>9,750</td></tr>
		  <tr><td style=\'background-color:#ccffdd\'>限定SDカード</td><td>1</td><td>10,000</td></tr>
		  <tr><td style=\'background-color:#ccffdd\'>BP回復薬（小）</td><td>3</td><td>10,500</td></tr>
		  <tr><td style=\'background-color:#ccffdd\'>BP回復薬（大）</td><td>1</td><td>11,000</td></tr>
		  <tr><td style=\'background-color:#ccffdd\'>BP回復薬（小）</td><td>3</td><td>11,500</td></tr>
		  <tr><td style=\'background-color:#ccffdd\'>ブルージュエル</td><td>1</td><td>12,000</td></tr>
		  <tr><td style=\'background-color:#ccffdd\'>レッドジュエル</td><td>1</td><td>12,500</td></tr>
		  <tr><td style=\'background-color:#ccffdd\'>BP回復薬（大）</td><td>1</td><td>13,000</td></tr>
		  <tr><td style=\'background-color:#ccffdd\'>BP回復薬（小）</td><td>3</td><td>13,500</td></tr>
		  <tr><td style=\'background-color:#ccffdd\'>ブルージュエル</td><td>1</td><td>14,000</td></tr>
		  <tr><td style=\'background-color:#ccffdd\'>レッドジュエル</td><td>1</td><td>14,500</td></tr>
		  <tr><td style=\'background-color:#ccffdd\'>BP回復薬（大）</td><td>1</td><td>15,000</td></tr>
		  <tr><td style=\'background-color:#ccffdd\'>体力回復薬（小）</td><td>1</td><td>16,000</td></tr>
		  <tr><td style=\'background-color:#ccffdd\'>ブルージュエル</td><td>1</td><td>17,000</td></tr>
		  <tr><td style=\'background-color:#ccffdd\'>BP回復薬（大）</td><td>1</td><td>18,000</td></tr>
		  <tr><td style=\'background-color:#ccffdd\'>レッドジュエル</td><td>1</td><td>19,000</td></tr>
		  <tr><td style=\'background-color:#ccffdd\'>スペシャルジュエル</td><td>1</td><td>20,000</td></tr>
		  <tr><td style=\'background-color:#ccffdd\'>鳳凰院メダル</td><td>1</td><td>21,000</td></tr>
		  <tr><td style=\'background-color:#ccffdd\'>鳳凰院メダル</td><td>1</td><td>22,000</td></tr>
		  <tr><td style=\'background-color:#ccffdd\'>鳳凰院メダル</td><td>1</td><td>23,000</td></tr>
		  <tr><td style=\'background-color:#ccffdd\'>鳳凰院メダル</td><td>1</td><td>24,000</td></tr>
		  <tr><td style=\'background-color:#ccffdd\'>鳳凰院メダル</td><td>1</td><td>25,000</td></tr>
		 
		 
		 
		 </td></tr>
		 </table>
		 
		 <br />
		 <br />
		 今後とも『ようこそ！恋ヶ崎女学園へ』をよろしくお願いいたします。<br />
		 </div>',
				  'new_arrival_start' => '2024-05-29 15:00:00',
				  'new_arrival_end' => '2024-06-10 10:00:00',
				  'enable' => 'Y',
				  'created' => '2024-05-22 11:54:37',
				  'updated' => '2024-05-22 11:54:37',
				  'is_new' => true,
				  'date' => '2024 05/29',
			   ),
			   2 => 
			   (object) array(
				  'announce_id' => '1824',
				  'subject' => '<b>十周年記念ログインボーナスのお知らせ</b>',
				  'description' => '<div style="width:800px; height:350px; overflow:auto;">
		 ユーザーの皆様へ<br /><br />
		 
		 いつも「ようこそ！恋ヶ崎女学園へ」をご愛顧くださり誠にありがとうございます。<br /><br />
		 
		 5月28日(火)AM5時から6月10日(月)AM5時まで、十周年記念ログインボーナスを実施<br />
		 させていただきます。<br /><br />
		 期間中にログインいただきますと、通常のログインボーナスに加えて、下記のアイテムを<br />
		 入手いただけます。<br /><br />
		 
		 【１日目】記念カード [十周年]桐夜＆千登里 1枚＋魔法石 5個<br />
		 【２日目】記念カード [十周年]桐夜＆千登里 1枚＋魔法石 5個<br />
		 【３日目】記念カード [十周年]桐夜＆千登里 1枚＋魔法石 5個<br />
		 【４日目】記念カード [十周年]桐夜＆千登里 1枚＋魔法石 5個<br />
		 【５日目】十周年補助券 5枚＋魔法石 5個<br />
		 【６日目】SP確定チケット 1枚＋魔法石 5個<br />
		 【７日目】選べるSPチケット 1枚＋魔法石 5個<br /><br />
		 
		 <font color="#ff0000">
		 ※選べるSPチケットは任意のSPカードから1枚選んで入手できるチケットです<br />
		  （有効期限は180日間となります）<br />
		 ※十周年補助券は5枚で限定カードから1枚選んで入手できるチケットです<br />
		  （有効期限は6月30日24時までとなります）<br /><br />
		 </font>
		 
		 なお、ログイン日の基準は通常のログインボーナスと同様に毎日AM5時となっております。<br /><br />
		 
		 例）5月28日の22時にログインし1日目のボーナスを入手した場合、次は5月29日AM5時以降に<br />
		 ログインした際に2日目のボーナスを入手する事ができます。<br /><br />
		 
		 <hr>
		 
		 今後とも「ようこそ！恋ヶ崎女学園へ」をよろしくお願いいたします。<br /><br />
		 </div>
		 ',
				  'new_arrival_start' => '2024-05-28 05:00:00',
				  'new_arrival_end' => '2024-06-10 05:00:00',
				  'enable' => 'Y',
				  'created' => '2024-05-17 20:47:02',
				  'updated' => '2024-05-17 21:07:18',
				  'is_new' => true,
				  'date' => '2024 05/28',
			   ),
			   3 => 
			   (object) array(
				  'announce_id' => '1825',
				  'subject' => '5月29日（水）メンテナンス予定のお知らせ(完了しました)',
				  'description' => '<div style="width:800px; height:350px; overflow:auto;">
		 ユーザーの皆様へ<br /><br />
		 
		 いつも「ようこそ！恋ヶ崎女学園へ」をご愛顧くださり誠にありがとうございます。<br /><br />
		 
		 5月29日（水）の13時～15時頃までの間、イベントの報酬配布およびバトルイベント準備のための<br />
		 メンテナンスを実施させていただく予定です。<br /><br />
		 
		 なお、メンテナンス終了時間は状況により変更する可能性がございますので、恐れ入りますが<br />
		 ご了承いただけますと幸いです。<br /><br />
		 
		 ユーザーの皆様にはご迷惑をおかけしてしまいますことをお詫びいたします。<br /><br />
		 
		 今後とも「ようこそ！恋ヶ崎女学園へ」をよろしくお願い致します。<br /><br />
		 </div>',
				  'new_arrival_start' => '2024-05-27 18:00:00',
				  'new_arrival_end' => '2024-05-29 13:00:00',
				  'enable' => 'Y',
				  'created' => '2024-05-20 14:48:54',
				  'updated' => '2024-05-29 15:07:22',
				  'is_new' => false,
				  'date' => '2024 05/27',
			   ),
			   4 => 
			   (object) array(
				  'announce_id' => '1823',
				  'subject' => '<b>十周年記念キャンペーン第一弾実施のお知らせ</b>',
				  'description' => '<div style="width:800px; height:350px; overflow:auto;">
		 ユーザーの皆様へ<br /><br />
		 
		 いつも「ようこそ！恋ヶ崎女学園へ」をご愛顧くださり誠にありがとうございます。<br /><br />
		 
		 5月27日(月)から6月10日(月)10時まで、十周年記念キャンペーン第一弾を実施させていただきます。<br /><br />
		 <hr>
		 <font color="#ff00ff"><b>
		 【十周年記念キャンペーン①】</font></b><br /><br />
		 <b>
		 期間中、魔法石50個で引ける『十周年記念限定ガチャ』が出現！<br />
		 プレミアムガチャと同内容のカードが排出され、SR～LEGENDの排出率が２倍！<br />
		 1回につき10枚排出され、必ずSPカード1枚が排出内容に含まれます。<br />
		 排出されるLEGENDカードは確定で新規LEGENDカードになります。<br />
		 さらに下記のオマケがついてくる！</b><br /><br />
		 
		 [オマケ]<br />
		 ・十周年補助券1枚<br />
		 ・SP補助券1枚<br />
		 ・こだわりミルク大：1枚<br />
		 ・スペシャルジュエル：4個<br />
		 ・レッドジュエル：5個<br />
		 ・ブルージュエル：6個<br /><br />
		 
		 <font color="#ff0000">※十周年補助券は5枚集めると限定カード1枚を選んで入手できます。<br />
		 ※十周年補助券の有効期限は6月30日24時までとなります。</font><br /><br />
		 
		 <b>[新規LEGENDカード]</b><br />
		 <font color="#ff0000">進化毎に表情とカードボイスが変化する特別仕様！</font><br />
		 ■[ベッド]紅葉<br />
		 　レアリティ：LEGEND<br />
		 　属性：キュート<br />
		 　最終攻撃力：25,151<br />
		 　最終防御力：22,001<br />
		 　（アビリティ）<br />
		 　キュートアタックS(S)<br />
		 　ハッピースマイルⅡ(S)<br />
		 　熱い大応援(S)<br /><br />
		 
		 ※上記LEGENDカードは、6月10日(月)10時までは『十周年記念限定ガチャ』からのみ排出されます。<br />
		 ※チケットからはキャンペーン開始から排出されます。<br /><br />
		 
		 <b>[限定カード]</b><br />
		 ■[限定]桜子<br />
		 └最終進化でSSR+まで進化します<br />
		 　2段階目の進化で導入ストーリー、3段階目の進化でHシーンをご覧いただけます<br />
		 【最終カードステータス】<br />
		 ・攻撃力：13548<br />
		 ・防御力：13548<br />
		 ・アビリティ1：セクシースマイルⅡ<br />
		 ・アビリティ2：セクシーインパクトS（4段階目）<br />
		 ・アビリティ3：優雅なティータイム（5段階目）<br /><br />
		 
		 ■[限定]ナナ<br />
		 └最終進化でSSR+まで進化します<br />
		 　2段階目の進化で導入ストーリー、3段階目の進化でHシーンをご覧いただけます<br />
		 【最終カードステータス】<br />
		 ・攻撃力：13548<br />
		 ・防御力：13548<br />
		 ・アビリティ1：いきなりアシスタントⅢ<br />
		 ・アビリティ2：クールテンションS（4段階目）<br />
		 ・アビリティ3：アルティメットラブⅡ（5段階目）<br /><br />
		 
		 ■[限定]沙月<br />
		 └最終進化でSSR+まで進化します<br />
		 　2段階目の進化で導入ストーリー、3段階目の進化でHシーンをご覧いただけます<br />
		 【最終カードステータス】<br />
		 ・攻撃力：13548<br />
		 ・防御力：13548<br />
		 ・アビリティ1：アクセル全開Ⅲ<br />
		 ・アビリティ2：キュートストライクS（4段階目）<br />
		 ・アビリティ3：キュートラブS（5段階目）<br /><br />
		 
		 ※限定カードは『十周年記念限定ガチャ』からのみ排出されます。<br />
		 ※限定カードは将来開催されるキャンペーンで再度登場する可能性があります。<br /><br />
		 
		 <b>ガチャ⇒『周年限定ガチャ』画面よりご利用いただけます。</b><br /><br />
		 
		 <hr>
		 <font color="#ff00ff"><b>
		 【十周年記念キャンペーン②】</font></b><br /><br />
		 <b>
		 期間中、5000DMMPで最大10回引ける『十周年記念特別ガチャ』が出現！<br />
		 プレミアムガチャと同内容のカードが排出され、SR～LEGENDの排出率が９倍！<br />
		 1回につき10枚排出され、必ずSPカード1枚が排出内容に含まれます。<br />
		 必ず下記の豪華オマケがついてくる！<br />
		 さらに利用回数の合計に応じたオマケもついてくる！</b><br /><br />
		 
		 [オマケ一覧]<br />
		 ・SP補助券：5枚<br />
		 ・こだわりミルク大：1枚<br />
		 ・スパルタコーチ大：1枚<br />
		 ・スペシャルジュエル：4個<br />
		 ・レッドジュエル：5個<br />
		 ・ブルージュエル：6個<br />
		 ・鳳凰院メダル：20枚<br /><br />
		 
		 [回数ボーナス]<br />
		 ・１回：【ランダム】SSR+カード1枚<br />
		 ・３回：L確定補助券1枚<br />
		 ・５回：選べるSPチケット1枚<br />
		 ・７回：【ランダム】SSR+カード1枚<br />
		 ・９回：L選択補助券1枚<br />
		 ・10回：選べるLEGENDチケット1枚<br /><br />
		 
		 <font color="#ff0000">※選べるSPチケットは任意のSPカードから1枚選んで入手できるチケットです<br />
		 ※選べるSPチケットの有効期限は180日間になります<br />
		 ※L確定補助券は5枚集めるとLEGENDカードを1枚入手できるチケットです<br />
		 ※L選択補助券は5枚集めると任意のLEGENDカードを1枚選んで入手できるチケットです<br />
		 ※選べるLEGENDチケットは任意のLEGENDカードを1枚選んで入手できるチケットです</font><br /><br />
		 
		 <b>ガチャ⇒『周年特別ガチャ』画面よりご利用いただけます。</b><br /><br />
		 
		 <hr>
		 <font color="#ff00ff"><b>
		 【十周年記念キャンペーン③】</font></b><br /><br />
		 
		 期間中、プレミアムガチャのSR～SSR+の排出率が３倍！<br />
		 プレミアムガチャで選べるオマケ候補のSPがナナを含め全種となり、確率も<b>50%</b>になる<br />
		 <font color="#ff00ff">SP50%キャンペーン</font>も同時開催中！<br /><br />
		 
		 <hr>
		 <font color="#ff00ff"><b>
		 【十周年記念キャンペーン④】</font></b><br /><br />
		 <b>
		 期間中、お得なセットの販売をいたします。<br />
		 （各セットはそれぞれ1回限りご購入が可能です）</b></font><br /><br />
		 
		 ・十周年記念セット①：500Pt<br />
		 └魔法石10個&オマケ（十周年補助券1枚）<br /><br />
		 
		 ・十周年記念セット②：2500Pt<br />
		 └魔法石50個&オマケ（十周年補助券2枚）<br /><br />
		 
		 ・十周年記念セット③：5000Pt<br />
		 └魔法石100個&オマケ（十周年補助券4枚）<br /><br />
		 
		 ・十周年記念セット④：10000Pt<br />
		 └魔法石200個&オマケ（十周年補助券8枚）<br /><br />
		 
		 <hr>
		 
		 今後とも「ようこそ！恋ヶ崎女学園へ」をよろしくお願いいたします。<br /><br />
		 </div>',
				  'new_arrival_start' => '2024-05-27 13:00:00',
				  'new_arrival_end' => '2024-06-10 10:00:00',
				  'enable' => 'Y',
				  'created' => '2024-05-17 20:44:30',
				  'updated' => '2024-05-17 20:44:30',
				  'is_new' => true,
				  'date' => '2024 05/27',
			   ),
			   5 => 
			   (object) array(
				  'announce_id' => '1830',
				  'subject' => '<b>十周年前夜祭キャンペーンのお知らせ</b>',
				  'description' => '<div style="width:800px; height:350px; overflow:auto;">
		 ユーザーの皆様へ<br /><br />
		 
		 いつも「ようこそ！恋ヶ崎女学園へ」をご愛顧くださり誠にありがとうございます。<br /><br />
		 
		 5月24日(月)から5月27日(月)13時まで、十周年前夜祭キャンペーンを実施させて<br />
		 いただきます。<br /><br />
		 
		 詳細につきましては、ゲーム画面内のインフォメーションおよびガチャ画面をご確認ください。<br /><br />
		 
		 <hr>
		 
		 今後とも「ようこそ！恋ヶ崎女学園へ」をよろしくお願いいたします。<br /><br />
		 </div>',
				  'new_arrival_start' => '2024-05-24 13:00:00',
				  'new_arrival_end' => '2024-05-27 13:00:00',
				  'enable' => 'Y',
				  'created' => '2024-05-26 22:42:00',
				  'updated' => '2024-05-26 22:42:00',
				  'is_new' => false,
				  'date' => '2024 05/24',
			   ),
			   6 => 
			   (object) array(
				  'announce_id' => '1821',
				  'subject' => '5月20日（月）メンテナンス予定のお知らせ',
				  'description' => '<div style="width:800px; height:350px; overflow:auto;">
		 ユーザーの皆様へ<br /><br />
		 
		 いつも「ようこそ！恋ヶ崎女学園へ」をご愛顧くださり誠にありがとうございます。<br /><br />
		 
		 5月20日（月）の13時～15時頃までの間、イベント後半準備のためのメンテナンスを実施<br />
		 させていただく予定です。<br /><br />
		 
		 なお、メンテナンス終了時間は状況により変更する可能性がございますので、恐れ入りますが<br />
		 ご了承いただけますと幸いです。<br /><br />
		 
		 ユーザーの皆様にはご迷惑をおかけ致しますことをお詫び申し上げます。<br /><br />
		 
		 今後とも「ようこそ！恋ヶ崎女学園へ」をよろしくお願い致します。<br /><br />
		 </div>',
				  'new_arrival_start' => '2024-05-17 18:00:00',
				  'new_arrival_end' => '2024-05-20 13:00:00',
				  'enable' => 'Y',
				  'created' => '2024-05-07 21:05:30',
				  'updated' => '2024-05-07 21:05:30',
				  'is_new' => false,
				  'date' => '2024 05/17',
			   ),
			   7 => 
			   (object) array(
				  'announce_id' => '1820',
				  'subject' => '週末キャンペーン・雨実施のお知らせ',
				  'description' => '<div style="width:800px; height:350px; overflow:auto;">
		 ユーザーの皆様へ<br /><br />
		 
		 いつも「ようこそ！恋ヶ崎女学園へ」をご愛顧くださり誠にありがとうございます。<br /><br />
		 
		 5月17日(金)から5月20日(月)13時まで、週末キャンペーン・雨を実施させていただきます。<br /><br />
		 <hr>
		 <font color="#ff00ff"><b>
		 【週末・雨ガチャ】</font></b><br /><br />
		 <b>
		 期間中５回まで引ける『週末・雨ガチャ』が出現！<br />
		 魔法石で引くことができ、豪華オマケがついてくる！<br /><br />
		 
		 ガチャ⇒『週末・雨ガチャ』画面よりご利用いただけます。</b><br /><br />
		 
		 詳細につきましては、ゲーム画面内のインフォメーションおよびガチャ画面をご確認ください。<br /><br />
		 
		 <hr>
		 
		 今後とも「ようこそ！恋ヶ崎女学園へ」をよろしくお願いいたします。<br /><br />
		 </div>',
				  'new_arrival_start' => '2024-05-17 13:00:00',
				  'new_arrival_end' => '2024-05-20 13:00:00',
				  'enable' => 'Y',
				  'created' => '2024-05-07 21:04:39',
				  'updated' => '2024-05-07 21:04:39',
				  'is_new' => false,
				  'date' => '2024 05/17',
			   ),
			   8 => 
			   (object) array(
				  'announce_id' => '1822',
				  'subject' => 'イベント限定キャンペーン対象限定カードの不具合のお知らせ
		 (5/20 14:15追記)',
				  'description' => '<div style="width:800px; height:350px; overflow:auto;">
		 ユーザーの皆様へ<br /><br />
		 
		 いつも「ようこそ！恋ヶ崎女学園へ」をご愛顧くださり誠にありがとうございます。<br /><br />
		 
		 <hr>
		 
		 5月13日（月）の13時00分より、イベント限定キャンペーンの対象限定カードである<br />
		 『[ﾗﾝｼﾞｪﾘｰ]明日香』につきまして、所持アビリティが正常に反映されない不具合が<br />
		 発生いたしておりましたが、5月20日(月)のメンテナンスにて修正を行いました。<br /><br />
		 
		 ユーザーの皆様には多大なご迷惑をおかけいたしましたことを深くお詫び申し上げます。<br /><br />
		 
		 今後とも「ようこそ！恋ヶ崎女学園へ」をよろしくお願い致します。<br /><br />
		 </div>',
				  'new_arrival_start' => '2024-05-13 19:30:00',
				  'new_arrival_end' => '2024-05-14 20:30:00',
				  'enable' => 'Y',
				  'created' => '2024-05-13 19:31:08',
				  'updated' => '2024-05-20 14:10:19',
				  'is_new' => false,
				  'date' => '2024 05/13',
			   ),
			   9 => 
			   (object) array(
				  'announce_id' => '1819',
				  'subject' => '<font color="#0000ff"><b>イベント応援キャンペーンのお知らせ 5/23追記</b></font>',
				  'description' => '<div style="width:800px; height:350px; overflow:auto;">
		 ユーザーの皆様へ<br /><br />
		 
		 いつも「ようこそ！恋ヶ崎女学園へ」をご愛顧くださり誠にありがとうございます。<br /><br />
		 
		 5月26日（日）23時まで、下記の期間限定キャンペーンを実施させていただきます。<br />
		 
		 
		 
		 <font color="#ff0000">※5月23日に『ラストスパートキャンペーン』を開始いたしました。</font><br />
		 
		 <hr>
		 
		 <b>
		 【キャンペーン内容】</b><br />
		 <hr>
		 
		 <br />
		 【ラストスパートキャンペーン】<br /><br />
		 
		 <font color="#ff00ff"><b>
		 ◆[初回]魔法石50個セット：2,800pt</font></b><br />
		 ※お一人様1セットのみご購入いただけます。<br /><br />
		 
		 <font color="#ff00ff"><b>
		 ◆[初回]BP小100個セット：1,000pt</font></b><br />
		 ※お一人様1セットのみご購入いただけます。<br /><br />
		 
		 <font color="#ff00ff"><b>
		 ◆BP全回復薬30個セット：2,000pt</font></b><br /><br />
		 
		 <font color="#ff00ff"><b>
		 ◆体力全回復薬30個セット：2,000pt</font></b><br /><br />
		 
		 <hr>
		 
		 <font color="#ff00ff"><b>
		 ◆ポイント特効セット①：1,000pt</font></b><br />
		 └<font color="#ff00ff"><b>ポイント特効（10%アップ）「[ﾗﾝｼﾞｪﾘｰ]綾芽☆」カード1枚</font></b>＆BP回復薬（大）20個<br /><br />
		 
		 <font color="#ff00ff"><b>
		 ◆ポイント特効セット②：2,000pt</font></b><br />
		 └<font color="#ff00ff"><b>ポイント特効（10%アップ）「[ﾗﾝｼﾞｪﾘｰ]綾芽☆」カード1枚</font></b>＆魔法石30個<br /><br />
		 
		 <font color="#ff00ff"><b>
		 ◆ポイント特効セット③：3,000pt</font></b><br />
		 └<font color="#ff00ff"><b>ポイント特効（10%アップ）「[ﾗﾝｼﾞｪﾘｰ]綾芽☆」カード1枚</font></b>＆魔法石45個<br /><br />
		 
		 <font color="#ff00ff"><b>
		 ◆ポイント特効セット④：5,000pt</font></b><br />
		 └<font color="#ff00ff"><b>ポイント特効（10%アップ）「[ﾗﾝｼﾞｪﾘｰ]綾芽☆」カード1枚</font></b>＆魔法石75個<br /><br />
		 
		 <font color="#ff00ff"><b>
		 ◆ポイント特効セット⑤：10,000pt</font></b><br />
		 └<font color="#ff00ff"><b>ポイント特効（10%アップ）「[ﾗﾝｼﾞｪﾘｰ]綾芽☆」カード1枚</font></b>＆魔法石150個<br /><br />
		 
		 <b>※すべて、お一人様1セットのみご購入いただけます。</b><br />
		 <hr>
		 
		 <font color="#ff00ff"><b>
		 ◆全体特効セット①：1,000pt</font></b><br />
		 └<font color="#ff00ff"><b>デッキ全体防御特効（全防50%アップ）「[ﾗﾝｼﾞｪﾘｰ]純花☆」カード1枚</font></b>＆BP回復薬（大）20個<br /><br />
		 
		 <font color="#ff00ff"><b>
		 ◆全体特効セット②：2,000pt</font></b><br />
		 └<font color="#ff00ff"><b>デッキ全体攻撃特効（全攻50%アップ）「[ﾗﾝｼﾞｪﾘｰ]ミシェル☆」カード1枚</font></b>＆魔法石30個<br /><br />
		 
		 <font color="#ff00ff"><b>
		 ◆全体特効セット③：3,000pt</font></b><br />
		 └<font color="#ff00ff"><b>デッキ全体防御特効（全防50%アップ）「[ﾗﾝｼﾞｪﾘｰ]純花☆」カード1枚</font></b>＆魔法石45個<br /><br />
		 
		 <font color="#ff00ff"><b>
		 ◆全体特効セット④：5,000pt</font></b><br />
		 └<font color="#ff00ff"><b>デッキ全体攻撃特効（全攻50%アップ）「[ﾗﾝｼﾞｪﾘｰ]ミシェル☆」カード1枚</font></b>＆魔法石75個<br /><br />
		 
		 <font color="#ff00ff"><b>
		 ◆全体特効セット⑤：10,000pt</font></b><br />
		 └<font color="#ff00ff"><b>デッキ全体攻撃・防御特効（全攻・防50%アップ）「[ﾗﾝｼﾞｪﾘｰ]ミシェル☆」「[ﾗﾝｼﾞｪﾘｰ]純花☆」カード1枚</font></b><br />
		 ＆魔法石150個<br /><br />
		 
		 <b>※すべて、お一人様1セットのみご購入いただけます。</b><br />
		 <hr>
		 
		 <font color="#ff00ff"><b>
		 ◆初心者専用イベント特効①：500pt</font></b><br />
		 └<font color="#ff00ff"><b>「[ﾗﾝｼﾞｪﾘｰ]初心者応援」カード1枚</font></b>＆魔法石10個＆「こだわりミルク（大）」カード1枚<br />
		 ※ご購入可能条件：ゲーム登録後90日以内の方。<br />
		 ※お一人様1セットのみご購入いただけます。<br /><br />
		 
		 <font color="#ff00ff"><b>
		 ◆初心者専用イベント特効②：500pt</font></b><br />
		 └<font color="#ff00ff"><b>「[ﾗﾝｼﾞｪﾘｰ]初心者応援」カード1枚</font></b>＆魔法石10個＆「こだわりミルク（大）」カード1枚<br />
		 ※ご購入可能条件：ゲーム登録後60日以内の方。<br />
		 ※お一人様1セットのみご購入いただけます。<br /><br />
		 
		 <font color="#ff00ff"><b>
		 ◆初心者専用イベント特効③：500pt</font></b><br />
		 └<font color="#ff00ff"><b>「[ﾗﾝｼﾞｪﾘｰ]初心者応援」カード1枚</font></b>＆魔法石10個＆「こだわりミルク（大）」カード1枚<br />
		 ※ご購入可能条件：ゲーム登録後30日以内の方。<br />
		 ※お一人様1セットのみご購入いただけます。<br /><br />
		 
		 ※「[ﾗﾝｼﾞｪﾘｰ]初心者応援」カードは、『胸キュン☆ラブリーランジェリー』イベント中、カード単体の攻撃・防御力が<br />
		 進化段階に応じて4倍・7倍・10倍になる非常に強力なカードです。<br />
		 <font color="#ff00ff"><b>特効込みの最終総力は100,440（リーダーカード設定時150,660）となります。</font></b><br /><br />
		 
		 ※「こだわりミルク（大）」カードは、強化素材専用のカードとなります。<br />
		 強化素材に使用いただくと、通常のカードを素材にするよりもたくさんの経験値を獲得出来ます。<br />
		 なお、経験値アップカードは、通常のカード（素材）と一緒に強化することは出来ませんので<br />
		 ご注意ください（経験値アップカードのみを素材カードに設定してください）。<br />
		 <hr>
		 
		 ご購入は、マイページ上部の『SHOP』よりお願いいたします。<br /><br />
		 
		 ※特効カードの特効効果は本イベント期間中のみ有効となります。<br /><br />
		 
		 今後とも『ようこそ！恋ヶ崎女学園へ』をよろしくお願いいたします。<br />
		 </div>
		 ',
				  'new_arrival_start' => '2024-05-13 13:00:00',
				  'new_arrival_end' => '2024-05-27 13:00:00',
				  'enable' => 'Y',
				  'created' => '2024-05-07 21:03:01',
				  'updated' => '2024-05-23 14:24:42',
				  'is_new' => false,
				  'date' => '2024 05/13',
			   ),
			 ),
		   ),
		 );

		return $this->responseJson($array);
	}


	

}