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

#[Annotation\Prefix('artwhirl')]
class ArtWhirlController extends BaseController
{
	private $mysqlIp = 'host.docker.internal:3308';

	private $userId = 15408309419429;

	public $dbInstance;

	#[Annotation\Route(method: 'GET', pattern: '/index')]
	public function index(Request $request)
	{
		return $this->render('/App/View/artwhirl/artwhirl.html');
	}

	#[Annotation\Route(method: 'GET', pattern: '/iframe')]
	public function iframe(Request $request)
	{
		return $this->render('/App/View/artwhirl/ifr.htm');
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

	#[Annotation\Route(method: 'POST', pattern: '/chara/getCostumeList')]
	public function getCostumeList(Request $request)
	{
		$array = array(
			'head' =>
				array(
					'user_data' =>
						array(
							'user_id' => $this->userId,
							'platform_id' => '24066034',
							'platform_name' => 'せくすせく',
							'platform_type' => 'r18',
							'hero_name' => '',
							'hero_name_id' => NULL,
							'user_lv' => 26,
							'user_exp' => 0,
							'ap' => 1418,
							'ap_updated' => '2021-11-29 00:08:00',
							'ep' => 48,
							'ep_updated' => '2021-11-29 00:08:00',
							'cxp' => 0,
							'cxxp' => 0,
							'favorite_chara_id' => 'ROLA',
							'current_board_index' => 0,
							'max_board_count' => 3,
							'last_modified_date' => '2021-11-29 01:20:50',
							'prestart_sp_date' => NULL,
							'days_count' => 0,
							'login_count' => 0,
							'tutorial_active' => 'Y',
							'debug_flag' => 'N',
							'status' => 1,
							'enable' => 'Y',
							'created' => '2018-10-30 01:35:41',
							'updated' => '2021-11-29 01:20:50',
							'ruins_unlock_key' => 0,
							'update_info' =>
								array(
									'login_bonus' => false,
									'notice' => false,
									'adv_notice' => true,
									'mission' => false,
								),
							'badge' =>
								array(
									'new_chara' => false,
									'new_ep' => false,
									'creation_completed' => false,
									'gacha_ticket_normal' => '650',
									'gacha_ticket_special' => 10,
									'present_count' => '232',
									'cxp' => '524',
									'cxxp' => '16',
									'good_count' => 0,
								),
						),
					'server' =>
						array(
							'timestamp' => 1638116450,
							'date' => '2021-11-29 01:20:50',
							'error_level' => 0,
							'error_page_id' => 0,
							'error_message' => '',
						),
				),
			'body' =>
				array(
					'chara' =>
						array(
							'chara_costume' =>
								array(
									0 =>
										array(
											'chara_id' => 2,
											'res_id' => 'ROLA',
											'costume_no' => 0,
											'atlas_res_id' =>
												array(
													'0' => NULL,
													'2' => NULL,
													'3' => NULL,
													'4' => NULL,
													'5' => NULL,
													'6' => NULL,
													'7' => NULL,
												),
										),
									1 =>
										array(
											'chara_id' => 4,
											'res_id' => 'MIKAN',
											'costume_no' => 0,
											'atlas_res_id' =>
												array(
													'0' => NULL,
													'2' => NULL,
													'3' => NULL,
													'4' => NULL,
													'5' => NULL,
													'6' => NULL,
													'7' => NULL,
													'8' => NULL,
												),
										),
									2 =>
										array(
											'chara_id' => 5,
											'res_id' => 'LUNA',
											'costume_no' => 0,
											'atlas_res_id' =>
												array(
													'0' => NULL,
													'2' => NULL,
													'3' => NULL,
													'4' => NULL,
													'5' => NULL,
													'6' => NULL,
													'7' => NULL,
												),
										),
									3 =>
										array(
											'chara_id' => 8,
											'res_id' => 'PRISCILA',
											'costume_no' => 0,
											'atlas_res_id' =>
												array(
													'0' => NULL,
													'2' => NULL,
													'3' => NULL,
													'4' => NULL,
													'5' => NULL,
													'6' => NULL,
													'7' => NULL,
												),
										),
								),
						),
				),
		);

		return $this->responseJson($array);
	}

	#[Annotation\Route(method: 'POST', pattern: '/user/login')]
	public function login(Request $request)
	{
		$array = array(
			'head' =>
				array(
					'user_data' =>
						array(
							'user_id' => $this->userId,
							'platform_id' => '24066034',
							'platform_name' => 'せくすせく',
							'platform_type' => 'r18',
							'hero_name' => '',
							'hero_name_id' => NULL,
							'user_lv' => 26,
							'user_exp' => 0,
							'ap' => 1418,
							'ap_updated' => '2021-11-29 00:08:00',
							'ep' => 48,
							'ep_updated' => '2021-11-29 00:08:00',
							'cxp' => 0,
							'cxxp' => 0,
							'favorite_chara_id' => 'ROLA',
							'current_board_index' => 0,
							'max_board_count' => 3,
							'last_modified_date' => '2021-11-29 01:20:50',
							'prestart_sp_date' => NULL,
							'days_count' => 0,
							'login_count' => 0,
							'tutorial_active' => 'Y',
							'debug_flag' => 'N',
							'status' => 1,
							'enable' => 'Y',
							'created' => '2018-10-30 01:35:41',
							'updated' => '2021-11-29 01:20:50',
							'ruins_unlock_key' => 0,
							'update_info' =>
								array(
									'login_bonus' => false,
									'notice' => false,
									'adv_notice' => false,
									'mission' => false,
								),
							'badge' =>
								array(
									'new_chara' => false,
									'new_ep' => false,
									'creation_completed' => false,
									'gacha_ticket_normal' => 0,
									'gacha_ticket_special' => 10,
									'present_count' => 0,
									'cxp' => 0,
									'cxxp' => 0,
									'good_count' => 0,
								),
						),
					'server' =>
						array(
							'timestamp' => 1638116450,
							'date' => '2021-11-29 01:20:50',
							'error_level' => 0,
							'error_page_id' => 0,
							'error_message' => '',
						),
				),
			'body' =>
				array(
					'owner_user_token' => '1f0fe08f9e8c403678d4d13cbf8c5471',
					'tutorial' => NULL,
				),
		);

		return $this->responseJson($array);
	}

	#[Annotation\Route(method: 'POST', pattern: '/game/boot')]
	public function boot(Request $request)
	{
		$array = array(
			'head' =>
				array(
					'user_data' =>
						array(
							'ruins_unlock_key' => 0,
							'favorite_chara_id' => 'MAHO',
							'hero_name' => '',
							'update_info' =>
								array(
									'login_bonus' => false,
									'notice' => false,
									'adv_notice' => false,
									'mission' => false,
								),
							'badge' =>
								array(
									'new_chara' => false,
									'new_ep' => false,
									'creation_completed' => false,
									'gacha_ticket_normal' => 0,
									'gacha_ticket_special' => 10,
									'present_count' => 0,
									'cxp' => 0,
									'cxxp' => 0,
									'good_count' => 0,
								),
						),
					'server' =>
						array(
							'timestamp' => 1638116379,
							'date' => '2021-11-29 01:19:39',
							'error_level' => 0,
							'error_page_id' => 0,
							'error_message' => '',
						),
				),
			'body' =>
				array(
					'boot' =>
						array(
							'maintenance' => false,
							'pre_registration' => false,
							'story_event' => true,
							'charas' =>
								array(
									0 => 'VANESSA',
									1 => 'ROLA',
									2 => 'DEE',
									3 => 'MIKAN',
									4 => 'LUNA',
									5 => 'SELES',
									6 => 'WISTERIA',
									7 => 'PRISCILA',
									8 => 'SAKURA',
									9 => 'AMANDA',
									10 => 'MAHO',
									11 => 'GINA',
									12 => 'CLAUDIA',
									13 => 'AMRITA',
									14 => 'DAHLIA',
									15 => 'PAULY',
									16 => 'NOEMI',
									17 => 'SNOW',
									18 => 'LUCE',
									19 => 'VICTORIA',
									20 => 'HILDA',
									21 => 'FLORIA',
									22 => 'CARUL',
									23 => 'CHACO',
									24 => 'COSETTE',
									25 => 'ULLR',
									26 => 'SYLVIA',
									27 => 'REN',
									28 => 'MAY',
									29 => 'NALU',
									30 => 'ELISE',
									31 => 'YUNI',
									32 => 'MIKOTO',
									33 => 'FREDERICA',
									34 => 'SOPHIE',
									35 => 'COCO',
									36 => 'CHIROL',
								),
						),
					'dmm_user_info' => NULL,
				),
		);

		return $this->responseJson($array);
	}

	#[Annotation\Route(method: 'POST', pattern: '/home/getHomeStatus')]
	public function getHomeStatus(Request $request)
	{
		$array = array(
			'head' =>
				array(
					'user_data' =>
						array(
							'user_id' => $this->userId,
							'platform_id' => '24066034',
							'platform_name' => 'せくすせく',
							'platform_type' => 'r18',
							'hero_name' => '',
							'hero_name_id' => NULL,
							'user_lv' => 26,
							'user_exp' => 0,
							'ap' => 1418,
							'ap_updated' => '2021-11-29 00:08:00',
							'ep' => 48,
							'ep_updated' => '2021-11-29 00:08:00',
							'cxp' => 0,
							'cxxp' => 0,
							'favorite_chara_id' => 'ROLA',
							'current_board_index' => 0,
							'max_board_count' => 3,
							'last_modified_date' => '2021-11-29 01:20:50',
							'prestart_sp_date' => NULL,
							'days_count' => 0,
							'login_count' => 0,
							'tutorial_active' => 'Y',
							'debug_flag' => 'N',
							'status' => 1,
							'enable' => 'Y',
							'created' => '2018-10-30 01:35:41',
							'updated' => '2021-11-29 01:20:50',
							'ruins_unlock_key' => 0,
							'update_info' =>
								array(
									'login_bonus' => false,
									'notice' => false,
									'adv_notice' => true,
									'mission' => false,
								),
							'badge' =>
								array(
									'new_chara' => false,
									'new_ep' => false,
									'creation_completed' => false,
									'gacha_ticket_normal' => '650',
									'gacha_ticket_special' => 10,
									'present_count' => '232',
									'cxp' => '524',
									'cxxp' => '16',
									'good_count' => 0,
								),
						),
					'server' =>
						array(
							'timestamp' => 1638116450,
							'date' => '2021-11-29 01:20:50',
							'error_level' => 0,
							'error_page_id' => 0,
							'error_message' => '',
						),
				),
			'body' =>
				array(
					'friend' =>
						array(
						),
					'update_info' =>
						array(
							'notice' => false,
							'login_bonus' => false,
							'mission' => 5,
							'event_point' => false,
							'ap' => false,
							'notice_force' => true,
							'adv_notice' => NULL,
						),
					'decoration' =>
						array(
							'gold' => 0,
							'silver' => 0,
							'copper' => 2,
						),
					'login_bonus' =>
						array(
						),
					'point_info' =>
						array(
							'ap_before' => 1418,
							'ap_after' => 1418,
							'ep_before' => 48,
							'ep_after' => 48,
						),
					'talk' =>
						array(
							'latest' =>
								array(
									0 =>
										array(
											'user_id' => $this->userId,
											'voice_id' => 'ROLA_talk_night_2',
											'platform_name' => 'せくすせく',
											'lv' => 26,
											'name' => 'ローラ',
											'res_id' => 'ROLA',
											'follow_status' => 0,
											'situation_type' => 'night',
											'talk' => 'よろしければ膝枕を\\nいたしましょうか？',
											'created' => '2021-11-29 01:20:51',
										),
									1 =>
										array(
											'user_id' => $this->userId,
											'voice_id' => 'ROLA_talk_night_2',
											'platform_name' => 'せくすせく',
											'lv' => 26,
											'name' => 'ローラ',
											'res_id' => 'ROLA',
											'follow_status' => 0,
											'situation_type' => 'night',
											'talk' => 'よろしければ膝枕を\\nいたしましょうか？',
											'created' => '2021-11-29 01:18:31',
										),
									2 =>
										array(
											'user_id' => $this->userId,
											'voice_id' => 'ROLA_talk_night_1',
											'platform_name' => 'せくすせく',
											'lv' => 26,
											'name' => 'ローラ',
											'res_id' => 'ROLA',
											'follow_status' => 0,
											'situation_type' => 'night',
											'talk' => '本日もご苦労様です\\n先生はとてもご立派ですわ',
											'created' => '2021-11-29 01:17:44',
										),
									3 =>
										array(
											'user_id' => $this->userId,
											'voice_id' => 'ROLA_talk_night_3',
											'platform_name' => 'せくすせく',
											'lv' => 26,
											'name' => 'ローラ',
											'res_id' => 'ROLA',
											'follow_status' => 0,
											'situation_type' => 'night',
											'talk' => '先生　良い夢を見られると\\nいいですわね',
											'created' => '2021-11-29 01:17:13',
										),
									4 =>
										array(
											'user_id' => $this->userId,
											'voice_id' => 'ROLA_talk_night_1',
											'platform_name' => 'せくすせく',
											'lv' => 26,
											'name' => 'ローラ',
											'res_id' => 'ROLA',
											'follow_status' => 0,
											'situation_type' => 'night',
											'talk' => '本日もご苦労様です\\n先生はとてもご立派ですわ',
											'created' => '2021-11-29 01:16:32',
										),
									5 =>
										array(
											'user_id' => $this->userId,
											'voice_id' => 'ROLA_talk_night_2',
											'platform_name' => 'せくすせく',
											'lv' => 26,
											'name' => 'ローラ',
											'res_id' => 'ROLA',
											'follow_status' => 0,
											'situation_type' => 'night',
											'talk' => 'よろしければ膝枕を\\nいたしましょうか？',
											'created' => '2021-11-29 01:16:07',
										),
									6 =>
										array(
											'user_id' => $this->userId,
											'voice_id' => 'ROLA_talk_night_3',
											'platform_name' => 'せくすせく',
											'lv' => 26,
											'name' => 'ローラ',
											'res_id' => 'ROLA',
											'follow_status' => 0,
											'situation_type' => 'night',
											'talk' => '先生　良い夢を見られると\\nいいですわね',
											'created' => '2021-11-29 01:14:26',
										),
									7 =>
										array(
											'user_id' => $this->userId,
											'voice_id' => 'ROLA_talk_night_1',
											'platform_name' => 'せくすせく',
											'lv' => 26,
											'name' => 'ローラ',
											'res_id' => 'ROLA',
											'follow_status' => 0,
											'situation_type' => 'night',
											'talk' => '本日もご苦労様です\\n先生はとてもご立派ですわ',
											'created' => '2021-11-29 01:13:50',
										),
								),
						),
					'battle' =>
						array(
							'break_data' => NULL,
						),
					'bubble' =>
						array(
							0 =>
								array(
									'campaign_id' => 1001,
									'type' => 'BUBBLE',
									'param' => 1,
									'value' => 0,
									'condition' => NULL,
									'startdate' => '2019-12-24 13:00:00',
									'enddate' => '2050-12-31 23:59:59',
									'memo' => '吹き出し「イベント開催中！」',
									'enable' => 'Y',
									'created' => '2020-12-21 12:20:00',
									'updated' => '2020-12-21 12:20:00',
								),
						),
				),
		);

		return $this->responseJson($array);
	}


	#[Annotation\Route(method: 'POST', pattern: '/panel/getPanelBoard')]
	public function getPanelBoard(Request $request)
	{
		$array = array(
			'head' =>
				array(
					'user_data' =>
						array(
							'user_id' => $this->userId,
							'platform_id' => '24066034',
							'platform_name' => 'せくすせく',
							'platform_type' => 'r18',
							'hero_name' => '',
							'hero_name_id' => NULL,
							'user_lv' => 26,
							'user_exp' => 0,
							'ap' => 1418,
							'ap_updated' => '2021-11-29 00:08:00',
							'ep' => 48,
							'ep_updated' => '2021-11-29 00:08:00',
							'cxp' => 0,
							'cxxp' => 0,
							'favorite_chara_id' => 'ROLA',
							'current_board_index' => 0,
							'max_board_count' => 3,
							'last_modified_date' => '2021-11-29 01:20:50',
							'prestart_sp_date' => NULL,
							'days_count' => 0,
							'login_count' => 0,
							'tutorial_active' => 'Y',
							'debug_flag' => 'N',
							'status' => 1,
							'enable' => 'Y',
							'created' => '2018-10-30 01:35:41',
							'updated' => '2021-11-29 01:20:50',
							'ruins_unlock_key' => 0,
							'update_info' =>
								array(
									'login_bonus' => false,
									'notice' => false,
									'adv_notice' => true,
									'mission' => false,
								),
							'badge' =>
								array(
									'new_chara' => false,
									'new_ep' => false,
									'creation_completed' => false,
									'gacha_ticket_normal' => '650',
									'gacha_ticket_special' => 10,
									'present_count' => '232',
									'cxp' => '524',
									'cxxp' => '16',
									'good_count' => 0,
								),
						),
					'server' =>
						array(
							'timestamp' => 1638116451,
							'date' => '2021-11-29 01:20:51',
							'error_level' => 0,
							'error_page_id' => 0,
							'error_message' => '',
						),
				),
			'body' =>
				array(
					'panel' =>
						array(
							'board' =>
								array(
									0 =>
										array(
											0 =>
												array(
													'res_id' => 'LUNA/11122',
													'sys_id' => 500011122,
												),
											1 => NULL,
											2 =>
												array(
													'res_id' => 'MIKAN/11321',
													'sys_id' => 400011321,
												),
											3 => NULL,
											4 => NULL,
											5 => NULL,
											6 =>
												array(
													'res_id' => 'LUNA/10312',
													'sys_id' => 500010312,
												),
											7 =>
												array(
													'res_id' => 'MIKAN/10111',
													'sys_id' => 400010111,
												),
											8 => NULL,
											9 =>
												array(
													'res_id' => 'PRISCILA/10011',
													'sys_id' => 800010011,
												),
											10 => NULL,
											11 =>
												array(
													'res_id' => 'LUNA/11012',
													'sys_id' => 500011012,
												),
											12 =>
												array(
													'res_id' => 'SELES/10231',
													'sys_id' => 600010231,
												),
											13 => NULL,
											14 => NULL,
											15 => NULL,
										),
									1 =>
										array(
											0 =>
												array(
													'res_id' => 'LUNA/11122',
													'sys_id' => 500011122,
												),
											1 => NULL,
											2 =>
												array(
													'res_id' => 'LUNA/11113',
													'sys_id' => 500011113,
												),
											3 => NULL,
											4 => NULL,
											5 => NULL,
											6 => NULL,
											7 => NULL,
											8 => NULL,
											9 => NULL,
											10 => NULL,
											11 => NULL,
											12 =>
												array(
													'res_id' => 'WISTERIA/10131',
													'sys_id' => 700010131,
												),
											13 => NULL,
											14 => NULL,
											15 => NULL,
										),
									2 =>
										array(
											0 => NULL,
											1 => NULL,
											2 => NULL,
											3 => NULL,
											4 => NULL,
											5 => NULL,
											6 => NULL,
											7 => NULL,
											8 => NULL,
											9 => NULL,
											10 => NULL,
											11 => NULL,
											12 => NULL,
											13 => NULL,
											14 => NULL,
											15 => NULL,
										),
								),
							'max_board_count' => 3,
							'current_board_index' => 0,
							'asset' =>
								array(
								),
							'board_size' =>
								array(
									0 => 1,
									1 => 1,
									2 => 1,
								),
						),
				),
		);

		return $this->responseJson($array);
	}

	#[Annotation\Route(method: 'POST', pattern: '/chara/getCharaStatus')]
	public function getCharaStatus(Request $request)
	{
		$array = array(
			'head' =>
				array(
					'user_data' =>
						array(
							'user_id' => $this->userId,
							'platform_id' => '24066034',
							'platform_name' => 'せくすせく',
							'platform_type' => 'r18',
							'hero_name' => '',
							'hero_name_id' => NULL,
							'user_lv' => 26,
							'user_exp' => 0,
							'ap' => 1418,
							'ap_updated' => '2021-11-29 00:08:00',
							'ep' => 48,
							'ep_updated' => '2021-11-29 00:08:00',
							'cxp' => 0,
							'cxxp' => 0,
							'favorite_chara_id' => 'ROLA',
							'current_board_index' => 0,
							'max_board_count' => 3,
							'last_modified_date' => '2021-11-29 01:20:50',
							'prestart_sp_date' => NULL,
							'days_count' => 0,
							'login_count' => 0,
							'tutorial_active' => 'Y',
							'debug_flag' => 'N',
							'status' => 1,
							'enable' => 'Y',
							'created' => '2018-10-30 01:35:41',
							'updated' => '2021-11-29 01:20:50',
							'ruins_unlock_key' => 0,
							'update_info' =>
								array(
									'login_bonus' => false,
									'notice' => false,
									'adv_notice' => true,
									'mission' => false,
								),
							'badge' =>
								array(
									'new_chara' => false,
									'new_ep' => false,
									'creation_completed' => false,
									'gacha_ticket_normal' => '650',
									'gacha_ticket_special' => 10,
									'present_count' => '232',
									'cxp' => '524',
									'cxxp' => '16',
									'good_count' => 0,
								),
						),
					'server' =>
						array(
							'timestamp' => 1638116451,
							'date' => '2021-11-29 01:20:51',
							'error_level' => 0,
							'error_page_id' => 0,
							'error_message' => '',
						),
				),
			'body' =>
				array(
					'chara' =>
						array(
							'status' =>
								array(
									'sys_id' => 2,
									'res_id' => 'ROLA',
									'name' => 'ローラ',
									'full_name' => 'ローラ',
									'costume_no' => 0,
									'lv' => 8,
									'max_lv' => 10,
									'unlocked' => true,
									'expired' => false,
									'open_date' => NULL,
									'close_date' => NULL,
									'sort_key' => 2,
									'clickable' => true,
									'progress' => 0,
									'pow' => 13,
									'vit' => 18,
									'agi' => 13,
									'std' => 14,
									'pow_plus' => 0,
									'vit_plus' => 0,
									'agi_plus' => 0,
									'std_plus' => 0,
									'pow_rank' => 'C',
									'vit_rank' => 'S',
									'agi_rank' => 'C',
									'std_rank' => 'B',
									'mhp' => 1715,
									'atk' => 65,
									'def' => 216,
									'equipped_weapon' => '大盾',
									'equipped_armor' => '重鎧,甲冑',
									'equipped_accesory' => '指輪,ブローチ,バングル,ガントレット,グリーブ',
									'description' => '【初等科】\\n西方にある『エストリア連合公国』の\\n領主である金獅子家の公女。\\n品行方正で、由緒正しい良家の娘。\\n素直で礼儀正しい優等生だが、家柄に\\n縛られた窮屈な生き方を続けている。\\n\\n大盾の扱いを得意とする防御型。\\n\\n\\n\\n＜攻撃：近距離・物理＞',
									'weapon' =>
										array(
											'name' => '訓練用の大盾',
											'res_id' => 'ewp_shield_sr_0',
											'category' => '大盾',
											'rank' => 0,
											'plus' => 0,
											'value' => 5,
											'ex' => '',
										),
									'armor' =>
										array(
											'name' => '銅の甲冑+2',
											'res_id' => 'eam_yoroi_1',
											'category' => '甲冑',
											'rank' => 1,
											'plus' => 2,
											'value' => 12,
											'ex' => '[HP大+2]',
										),
									'accessory_0' =>
										array(
											'name' => 'グリーブ',
											'res_id' => 'eac_shinguard_1',
											'category' => 'グリーブ',
											'rank' => 1,
											'plus' => 0,
											'value' => 10,
											'ex' => '[癒+0]',
										),
									'accessory_1' => '',
									'memories' => NULL,
									'memories_slot' =>
										array(
											'1' =>
												array(
													'memories_id' => 100023,
													'res_id' => 'ROLA/10_3',
													'name' => '楽しい思い出★3',
													'slot_no' => 1,
													'equip_slot_no' => 1,
													'effect_list' =>
														array(
															0 =>
																array(
																	'rune_effect_type_id' => 18,
																	'rune_effect_type' => 'mhp',
																	'rate_type' => 'ratio',
																	'memo' => '最大HP＋〇%',
																	'enable' => 'Y',
																	'created' => '2019-02-06 00:00:00',
																	'updated' => '2019-02-06 00:00:00',
																	'value' => 1,
																	'reflection_type' => 'up',
																	'attribute_id' => 0,
																	'event_id' => 0,
																),
														),
													'effect_list_battle' =>
														array(
														),
													'ex' => '最大HP+1％',
													'event_sp_info' => NULL,
												),
											'2' =>
												array(
													'memories_id' => NULL,
													'res_id' => NULL,
													'name' => 'みんなの思い出',
													'effect_list' =>
														array(
														),
													'effect_list_battle' =>
														array(
														),
													'ex' => NULL,
												),
										),
									'costume_data' =>
										array(
										),
									'illustrator' => '',
									'illustrator_r18_enable' => true,
									'voice_actor' => '',
									'special' =>
										array(
											'lv' => 0,
											'name' => 'シールドチャージ',
											'description' => '【※未習得】\\n\\nスペシャル魔法Lvが上がると、突進攻撃を行い、防御効果を得られるようになります。\\n\\nスペシャル魔法Lvはローラのヒロインストーリーの特定のバトルを★3クリアすると上がります。',
											'ex_skill' => false,
										),
									'support' =>
										array(
											'lv' => 0,
											'name' => 'クイックガード',
											'description' => '【※未習得】\\n\\nサポート魔法Lvが上がると、リーダーへのダメージを軽減できるようになります。\\n\\nサポート魔法Lvはローラのヒロインストーリーの特定のバトルを★3クリアすると上がります。',
											'ex_skill' => false,
										),
									'passive' =>
										array(
											'lv' => 0,
											'name' => '浮遊する守護の盾',
											'description' => '【※未習得】\\n\\n戦闘魔法Lvが上がると『守護の盾』が発生します。\\n\\n味方への物理攻撃をガードしてくれます。\\n\\n戦闘魔法Lvはローラの\\nヒロインストーリーの特定のバトルを★3クリアすると上がります。',
											'ex_skill' => false,
										),
									'study' =>
										array(
											'lv' => 0,
											'name' => '美味しい紅茶を淹れるように',
											'description' => '【※未習得】\\n\\n研究魔法Lvが上がると装備生成に\\n役立つ効果が発生するようになります。\\n\\n研究魔法Lvはローラの\\nヒロインストーリーの特定のバトルを\\n★3クリアすると上がります。',
											'ex_skill' => false,
										),
								),
							'status_up_info' =>
								array(
									'cxp_use_type' => 0,
									'cxxp_use_type' => 0,
									'cxp' => NULL,
									'cxp_id' => NULL,
									'cxxp' => NULL,
									'cxxp_id' => NULL,
									'general_cxp_id' => 1,
									'general_cxxp_id' => 2,
									'unlock_use_cxxp' => 10,
									'level_up_info' =>
										array(
											'8' =>
												array(
													'cxp' => 55,
													'pow' => 13,
													'vit' => 18,
													'agi' => 13,
													'std' => 14,
													'hp' => 1715,
													'atk' => 65,
													'def' => 216,
												),
											'9' =>
												array(
													'cxp' => 66,
													'pow' => 13,
													'vit' => 19,
													'agi' => 13,
													'std' => 15,
													'hp' => 1842,
													'atk' => 65,
													'def' => 228,
												),
											'10' =>
												array(
													'cxp' => 78,
													'pow' => 14,
													'vit' => 21,
													'agi' => 14,
													'std' => 16,
													'hp' => 2126,
													'atk' => 70,
													'def' => 252,
												),
										),
									'level_max_up_use_cxxp' =>
										array(
											'10' => 10,
											'15' => 20,
											'20' => 20,
											'25' => 30,
											'30' => 40,
											'35' => 40,
											'40' => 50,
											'45' => 50,
											'50' => 60,
											'55' => 60,
											'60' => 200,
											'65' => 200,
											'70' => 250,
										),
									'chara_get_type' => 'normal',
								),
							'quest_id' => 2002,
							'quest_chara_unlock_lv' => 1,
							'costume_list' =>
								array(
									0 => 0,
								),
						),
				),
		);

		return $this->responseJson($array);
	}

	#[Annotation\Route(method: 'POST', pattern: '/chara/getCharaGroup')]
	public function getCharaGroup(Request $request)
	{
		$array = array(
			'head' =>
				array(
					'user_data' =>
						array(
							'user_id' => $this->userId,
							'platform_id' => '24066034',
							'platform_name' => 'せくすせく',
							'platform_type' => 'r18',
							'hero_name' => '',
							'hero_name_id' => NULL,
							'user_lv' => 26,
							'user_exp' => 0,
							'ap' => 1418,
							'ap_updated' => '2021-11-29 00:08:00',
							'ep' => 48,
							'ep_updated' => '2021-11-29 00:08:00',
							'cxp' => 0,
							'cxxp' => 0,
							'favorite_chara_id' => 'ROLA',
							'current_board_index' => 0,
							'max_board_count' => 3,
							'last_modified_date' => '2021-11-29 01:20:50',
							'prestart_sp_date' => NULL,
							'days_count' => 0,
							'login_count' => 0,
							'tutorial_active' => 'Y',
							'debug_flag' => 'N',
							'status' => 1,
							'enable' => 'Y',
							'created' => '2018-10-30 01:35:41',
							'updated' => '2021-11-29 01:20:50',
							'ruins_unlock_key' => 0,
							'update_info' =>
								array(
									'login_bonus' => false,
									'notice' => false,
									'adv_notice' => true,
									'mission' => false,
								),
							'badge' =>
								array(
									'new_chara' => false,
									'new_ep' => false,
									'creation_completed' => false,
									'gacha_ticket_normal' => '650',
									'gacha_ticket_special' => 10,
									'present_count' => '232',
									'cxp' => '524',
									'cxxp' => '16',
									'good_count' => 0,
								),
						),
					'server' =>
						array(
							'timestamp' => 1638116451,
							'date' => '2021-11-29 01:20:51',
							'error_level' => 0,
							'error_page_id' => 0,
							'error_message' => '',
						),
				),
			'body' =>
				array(
					'chara' =>
						array(
							'group' =>
								array(
									0 =>
										array(
											'sys_id' => 1,
											'res_id' => 'VANESSA',
											'atlas_res_id' =>
												array(
													'0' => NULL,
													'2' => NULL,
													'3' => NULL,
													'4' => NULL,
													'5' => NULL,
												),
											'name' => 'ヴァネッサ',
											'clickable' => true,
											'mhp' => 200,
											'hp' => NULL,
											'lv' => 1,
											'unlocked' => false,
											'progress' => 0,
											'expired' => false,
											'open_date' => NULL,
											'close_date' => NULL,
											'last_days' => NULL,
											'sort_key' => 2,
											'std' => 8,
											'cxp_use_type' => 0,
											'cxxp_use_type' => 0,
											'cxp' => NULL,
											'cxxp' => NULL,
											'study' =>
												array(
													'chara_magic_study_id' => 1,
													'name' => 'こんなはずではありませんわ',
													'description' => '【※未習得】\\n\\n研究魔法Lvが上がると装備生成に\\n役立つ効果が発生するようになります。\\n\\n研究魔法Lvはヴァネッサの\\nヒロインストーリーの特定のバトルを\\n★3クリアすると上がります。',
													'magic' =>
														array(
														),
													'lv' => 0,
												),
											'event_sp_info' => NULL,
										),
									1 =>
										array(
											'sys_id' => 2,
											'res_id' => 'ROLA',
											'atlas_res_id' =>
												array(
													'0' => NULL,
													'2' => NULL,
													'3' => NULL,
													'4' => NULL,
													'5' => NULL,
													'6' => NULL,
													'7' => NULL,
												),
											'name' => 'ローラ',
											'clickable' => true,
											'mhp' => 200,
											'hp' => NULL,
											'lv' => 8,
											'unlocked' => true,
											'progress' => 0,
											'expired' => false,
											'open_date' => NULL,
											'close_date' => NULL,
											'last_days' => NULL,
											'sort_key' => 2,
											'std' => 14,
											'cxp_use_type' => 0,
											'cxxp_use_type' => 0,
											'cxp' => NULL,
											'cxxp' => NULL,
											'study' =>
												array(
													'chara_magic_study_id' => 2,
													'name' => '美味しい紅茶を淹れるように',
													'description' => '【※未習得】\\n\\n研究魔法Lvが上がると装備生成に\\n役立つ効果が発生するようになります。\\n\\n研究魔法Lvはローラの\\nヒロインストーリーの特定のバトルを\\n★3クリアすると上がります。',
													'magic' =>
														array(
														),
													'lv' => 0,
												),
											'event_sp_info' => NULL,
										),
									2 =>
										array(
											'sys_id' => 3,
											'res_id' => 'DEE',
											'atlas_res_id' =>
												array(
													'0' => NULL,
													'2' => NULL,
													'3' => NULL,
													'4' => NULL,
													'5' => NULL,
												),
											'name' => 'ディー',
											'clickable' => true,
											'mhp' => 200,
											'hp' => NULL,
											'lv' => 1,
											'unlocked' => false,
											'progress' => 0,
											'expired' => false,
											'open_date' => NULL,
											'close_date' => NULL,
											'last_days' => NULL,
											'sort_key' => 2,
											'std' => 7,
											'cxp_use_type' => 0,
											'cxxp_use_type' => 0,
											'cxp' => NULL,
											'cxxp' => NULL,
											'study' =>
												array(
													'chara_magic_study_id' => 3,
													'name' => '思い切ってドバーッと',
													'description' => '【※未習得】\\n\\n研究魔法Lvが上がると装備生成に\\n役立つ効果が発生するようになります。\\n\\n研究魔法Lvはディーの\\nヒロインストーリーの特定のバトルを\\n★3クリアすると上がります。',
													'magic' =>
														array(
														),
													'lv' => 0,
												),
											'event_sp_info' => NULL,
										),
									3 =>
										array(
											'sys_id' => 4,
											'res_id' => 'MIKAN',
											'atlas_res_id' =>
												array(
													'0' => NULL,
													'2' => NULL,
													'3' => NULL,
													'4' => NULL,
													'5' => NULL,
													'6' => NULL,
													'7' => NULL,
													'8' => NULL,
												),
											'name' => 'ミカン',
											'clickable' => true,
											'mhp' => 200,
											'hp' => NULL,
											'lv' => 13,
											'unlocked' => true,
											'progress' => 0,
											'expired' => false,
											'open_date' => NULL,
											'close_date' => NULL,
											'last_days' => NULL,
											'sort_key' => 2,
											'std' => 18,
											'cxp_use_type' => 0,
											'cxxp_use_type' => 0,
											'cxp' => NULL,
											'cxxp' => NULL,
											'study' =>
												array(
													'chara_magic_study_id' => 4,
													'name' => 'イチかバチかやっちゃえ',
													'description' => '【※未習得】\\n\\n研究魔法Lvが上がると装備生成に\\n役立つ効果が発生するようになります。\\n\\n研究魔法Lvはミカンの\\nヒロインストーリーの特定のバトルを\\n★3クリアすると上がります。',
													'magic' =>
														array(
														),
													'lv' => 0,
												),
											'event_sp_info' => NULL,
										),
									4 =>
										array(
											'sys_id' => 5,
											'res_id' => 'LUNA',
											'atlas_res_id' =>
												array(
													'0' => NULL,
													'2' => NULL,
													'3' => NULL,
													'4' => NULL,
													'5' => NULL,
													'6' => NULL,
													'7' => NULL,
												),
											'name' => 'ルナ',
											'clickable' => true,
											'mhp' => 200,
											'hp' => NULL,
											'lv' => 10,
											'unlocked' => true,
											'progress' => 0,
											'expired' => false,
											'open_date' => NULL,
											'close_date' => NULL,
											'last_days' => NULL,
											'sort_key' => 2,
											'std' => 18,
											'cxp_use_type' => 0,
											'cxxp_use_type' => 0,
											'cxp' => NULL,
											'cxxp' => NULL,
											'study' =>
												array(
													'chara_magic_study_id' => 5,
													'name' => 'これだけあれば十分です',
													'description' => '【※未習得】\\n\\n研究魔法Lvが上がると装備生成に\\n役立つ効果が発生するようになります。\\n\\n研究魔法Lvはルナの\\nヒロインストーリーの特定のバトルを\\n★3クリアすると上がります。',
													'magic' =>
														array(
														),
													'lv' => 0,
												),
											'event_sp_info' => NULL,
										),
									5 =>
										array(
											'sys_id' => 6,
											'res_id' => 'SELES',
											'atlas_res_id' =>
												array(
													'0' => NULL,
													'2' => NULL,
													'3' => NULL,
													'4' => NULL,
													'5' => NULL,
													'6' => NULL,
													'7' => NULL,
													'8' => NULL,
												),
											'name' => 'セレス',
											'clickable' => true,
											'mhp' => 200,
											'hp' => NULL,
											'lv' => 1,
											'unlocked' => false,
											'progress' => 0,
											'expired' => false,
											'open_date' => NULL,
											'close_date' => NULL,
											'last_days' => NULL,
											'sort_key' => 2,
											'std' => 9,
											'cxp_use_type' => 0,
											'cxxp_use_type' => 0,
											'cxp' => NULL,
											'cxxp' => NULL,
											'study' =>
												array(
													'chara_magic_study_id' => 6,
													'name' => 'きちんと的確に',
													'description' => '【※未習得】\\n\\n研究魔法Lvが上がると装備生成に\\n役立つ効果が発生するようになります。\\n\\n研究魔法Lvはセレスの\\nヒロインストーリーの特定のバトルを\\n★3クリアすると上がります。',
													'magic' =>
														array(
														),
													'lv' => 0,
												),
											'event_sp_info' => NULL,
										),
									6 =>
										array(
											'sys_id' => 7,
											'res_id' => 'WISTERIA',
											'atlas_res_id' =>
												array(
													'0' => NULL,
													'2' => NULL,
													'3' => NULL,
													'4' => NULL,
													'5' => NULL,
												),
											'name' => 'ウィステリア',
											'clickable' => true,
											'mhp' => 200,
											'hp' => NULL,
											'lv' => 10,
											'unlocked' => false,
											'progress' => 0,
											'expired' => false,
											'open_date' => NULL,
											'close_date' => NULL,
											'last_days' => NULL,
											'sort_key' => 2,
											'std' => 19,
											'cxp_use_type' => 0,
											'cxxp_use_type' => 0,
											'cxp' => NULL,
											'cxxp' => NULL,
											'study' =>
												array(
													'chara_magic_study_id' => 7,
													'name' => '試してみたいやり方があります',
													'description' => 'ルーンスロットが1％付きやすく\\nなります。\\n\\n必要素材数が15％増えます。\\n\\n完成するまでの時間が\\n15％長くなります。\\n\\n研究魔法Lvが上がると、\\n効果が上がります。',
													'magic' =>
														array(
														),
													'lv' => 1,
												),
											'event_sp_info' => NULL,
										),
									7 =>
										array(
											'sys_id' => 8,
											'res_id' => 'PRISCILA',
											'atlas_res_id' =>
												array(
													'0' => NULL,
													'2' => NULL,
													'3' => NULL,
													'4' => NULL,
													'5' => NULL,
													'6' => NULL,
													'7' => NULL,
												),
											'name' => 'プリシラ',
											'clickable' => true,
											'mhp' => 200,
											'hp' => NULL,
											'lv' => 15,
											'unlocked' => true,
											'progress' => 0,
											'expired' => false,
											'open_date' => NULL,
											'close_date' => NULL,
											'last_days' => NULL,
											'sort_key' => 2,
											'std' => 22,
											'cxp_use_type' => 0,
											'cxxp_use_type' => 0,
											'cxp' => NULL,
											'cxxp' => NULL,
											'study' =>
												array(
													'chara_magic_study_id' => 8,
													'name' => 'ちゃんとできるよ',
													'description' => '成功率が4％上がります。\\n\\nプラス補正が1％付きにくくなります。\\n\\n研究魔法Lvが上がると、\\n効果が上がります。',
													'magic' =>
														array(
														),
													'lv' => 1,
												),
											'event_sp_info' => NULL,
										),
									8 =>
										array(
											'sys_id' => 9,
											'res_id' => 'SAKURA',
											'atlas_res_id' =>
												array(
													'0' => NULL,
													'2' => NULL,
													'3' => NULL,
													'4' => NULL,
													'5' => NULL,
													'6' => NULL,
													'7' => NULL,
													'8' => NULL,
												),
											'name' => 'サクラ',
											'clickable' => true,
											'mhp' => 200,
											'hp' => NULL,
											'lv' => 10,
											'unlocked' => false,
											'progress' => 0,
											'expired' => false,
											'open_date' => NULL,
											'close_date' => NULL,
											'last_days' => NULL,
											'sort_key' => 2,
											'std' => 15,
											'cxp_use_type' => 0,
											'cxxp_use_type' => 0,
											'cxp' => NULL,
											'cxxp' => NULL,
											'study' =>
												array(
													'chara_magic_study_id' => 9,
													'name' => 'あまり得意ではないのですが',
													'description' => 'ルーンスロットが1％付きやすく\\nなります。\\n\\n成功率が20％下がります。\\n\\n研究魔法Lvが上がると、\\n効果が上がります。',
													'magic' =>
														array(
														),
													'lv' => 1,
												),
											'event_sp_info' => NULL,
										),
									9 =>
										array(
											'sys_id' => 10,
											'res_id' => 'AMANDA',
											'atlas_res_id' =>
												array(
													'0' => NULL,
													'2' => NULL,
													'3' => NULL,
													'4' => NULL,
												),
											'name' => 'アマンダ',
											'clickable' => true,
											'mhp' => 200,
											'hp' => NULL,
											'lv' => 10,
											'unlocked' => false,
											'progress' => 0,
											'expired' => false,
											'open_date' => NULL,
											'close_date' => NULL,
											'last_days' => NULL,
											'sort_key' => 2,
											'std' => 17,
											'cxp_use_type' => 0,
											'cxxp_use_type' => 0,
											'cxp' => NULL,
											'cxxp' => NULL,
											'study' =>
												array(
													'chara_magic_study_id' => 10,
													'name' => 'お姉さんに任せて',
													'description' => '成功率が4％上がります。\\n\\n必要素材数が15％増えます。\\n\\n研究魔法Lvが上がると、\\n効果が上がります。',
													'magic' =>
														array(
														),
													'lv' => 1,
												),
											'event_sp_info' => NULL,
										),
									10 =>
										array(
											'sys_id' => 11,
											'res_id' => 'MAHO',
											'atlas_res_id' =>
												array(
													'0' => NULL,
													'2' => NULL,
													'3' => NULL,
													'4' => NULL,
													'5' => 'MAHOCHILDEARLY',
													'6' => NULL,
												),
											'name' => 'マホ',
											'clickable' => true,
											'mhp' => 200,
											'hp' => NULL,
											'lv' => 20,
											'unlocked' => false,
											'progress' => 0,
											'expired' => false,
											'open_date' => NULL,
											'close_date' => NULL,
											'last_days' => NULL,
											'sort_key' => 2,
											'std' => 30,
											'cxp_use_type' => 0,
											'cxxp_use_type' => 0,
											'cxp' => NULL,
											'cxxp' => NULL,
											'study' =>
												array(
													'chara_magic_study_id' => 11,
													'name' => 'お手本を見せるわね',
													'description' => '完成するまでの時間が\\n15％短くなります。\\n\\n10％の確率で失敗をキャンセルして\\n成功にします。\\n\\n失敗時の素材返還数が15％減ります。\\n\\n研究魔法Lvが上がると、\\n効果が上がります。',
													'magic' =>
														array(
														),
													'lv' => 1,
												),
											'event_sp_info' => NULL,
										),
									11 =>
										array(
											'sys_id' => 12,
											'res_id' => 'GINA',
											'atlas_res_id' =>
												array(
													'0' => NULL,
													'2' => NULL,
													'3' => NULL,
													'4' => NULL,
													'5' => NULL,
													'6' => NULL,
													'7' => NULL,
													'8' => NULL,
												),
											'name' => 'ジーナ',
											'clickable' => true,
											'mhp' => 200,
											'hp' => NULL,
											'lv' => 20,
											'unlocked' => false,
											'progress' => 0,
											'expired' => false,
											'open_date' => NULL,
											'close_date' => NULL,
											'last_days' => NULL,
											'sort_key' => 2,
											'std' => 23,
											'cxp_use_type' => 0,
											'cxxp_use_type' => 0,
											'cxp' => NULL,
											'cxxp' => NULL,
											'study' =>
												array(
													'chara_magic_study_id' => 12,
													'name' => '苦手でも頑張る',
													'description' => '完成するまでの時間が\\n15％長くなります。\\n\\n失敗時の素材返還数が15％増えます。\\n\\n研究魔法Lvが上がると、\\n効果が上がります。',
													'magic' =>
														array(
														),
													'lv' => 1,
												),
											'event_sp_info' => NULL,
										),
									12 =>
										array(
											'sys_id' => 13,
											'res_id' => 'CLAUDIA',
											'atlas_res_id' =>
												array(
													'0' => NULL,
													'2' => NULL,
													'3' => NULL,
													'4' => NULL,
													'5' => NULL,
													'6' => NULL,
												),
											'name' => 'クラウディア',
											'clickable' => true,
											'mhp' => 200,
											'hp' => NULL,
											'lv' => 20,
											'unlocked' => false,
											'progress' => 0,
											'expired' => false,
											'open_date' => NULL,
											'close_date' => NULL,
											'last_days' => NULL,
											'sort_key' => 2,
											'std' => 34,
											'cxp_use_type' => 0,
											'cxxp_use_type' => 0,
											'cxp' => NULL,
											'cxxp' => NULL,
											'study' =>
												array(
													'chara_magic_study_id' => 13,
													'name' => '少し欲張ってしまいます',
													'description' => 'プラス補正が1％付きやすくなります。\\n\\n必要素材数が15％増えます。\\n\\n完成するまでの時間が\\n15％短くなります。\\n\\n研究魔法Lvが上がると、\\n効果が上がります。',
													'magic' =>
														array(
														),
													'lv' => 1,
												),
											'event_sp_info' => NULL,
										),
									13 =>
										array(
											'sys_id' => 14,
											'res_id' => 'AMRITA',
											'atlas_res_id' =>
												array(
													'0' => NULL,
													'2' => NULL,
													'3' => NULL,
												),
											'name' => 'アムリタ',
											'clickable' => true,
											'mhp' => 200,
											'hp' => NULL,
											'lv' => 20,
											'unlocked' => false,
											'progress' => 0,
											'expired' => false,
											'open_date' => NULL,
											'close_date' => NULL,
											'last_days' => NULL,
											'sort_key' => 2,
											'std' => 30,
											'cxp_use_type' => 0,
											'cxxp_use_type' => 0,
											'cxp' => NULL,
											'cxxp' => NULL,
											'study' =>
												array(
													'chara_magic_study_id' => 14,
													'name' => 'のんびりじっくり',
													'description' => '成功率が4％上がります。\\n\\n完成するまでの時間が\\n15％長くなります。\\n\\n研究魔法Lvが上がると、\\n効果が上がります。',
													'magic' =>
														array(
														),
													'lv' => 1,
												),
											'event_sp_info' => NULL,
										),
									14 =>
										array(
											'sys_id' => 15,
											'res_id' => 'DAHLIA',
											'atlas_res_id' =>
												array(
													'0' => NULL,
													'2' => NULL,
													'3' => NULL,
												),
											'name' => 'ダリア',
											'clickable' => true,
											'mhp' => 200,
											'hp' => NULL,
											'lv' => 10,
											'unlocked' => false,
											'progress' => 0,
											'expired' => false,
											'open_date' => NULL,
											'close_date' => NULL,
											'last_days' => NULL,
											'sort_key' => 2,
											'std' => 17,
											'cxp_use_type' => 0,
											'cxxp_use_type' => 0,
											'cxp' => NULL,
											'cxxp' => NULL,
											'study' =>
												array(
													'chara_magic_study_id' => 15,
													'name' => 'こういうのは得意だから',
													'description' => '完成するまでの時間が\\n15％短くなります。\\n\\n失敗時の素材返還数が15％増えます。\\n\\n研究魔法Lvが上がると、\\n効果が上がります。',
													'magic' =>
														array(
														),
													'lv' => 1,
												),
											'event_sp_info' => NULL,
										),
									15 =>
										array(
											'sys_id' => 16,
											'res_id' => 'PAULY',
											'atlas_res_id' =>
												array(
													'0' => NULL,
													'2' => NULL,
													'3' => NULL,
													'4' => NULL,
													'5' => NULL,
												),
											'name' => 'ポーリィ',
											'clickable' => true,
											'mhp' => 200,
											'hp' => NULL,
											'lv' => 10,
											'unlocked' => false,
											'progress' => 0,
											'expired' => false,
											'open_date' => NULL,
											'close_date' => NULL,
											'last_days' => NULL,
											'sort_key' => 2,
											'std' => 15,
											'cxp_use_type' => 0,
											'cxxp_use_type' => 0,
											'cxp' => NULL,
											'cxxp' => NULL,
											'study' =>
												array(
													'chara_magic_study_id' => 16,
													'name' => 'いまのは無しです！',
													'description' => '10％の確率で失敗をキャンセルして\\n成功にします。\\n\\n研究魔法Lvが上がると、\\n効果が上がります。',
													'magic' =>
														array(
														),
													'lv' => 1,
												),
											'event_sp_info' => NULL,
										),
									16 =>
										array(
											'sys_id' => 17,
											'res_id' => 'NOEMI',
											'atlas_res_id' =>
												array(
													'0' => NULL,
													'2' => NULL,
													'3' => NULL,
												),
											'name' => 'ノエミ',
											'clickable' => true,
											'mhp' => 200,
											'hp' => NULL,
											'lv' => 10,
											'unlocked' => false,
											'progress' => 0,
											'expired' => false,
											'open_date' => NULL,
											'close_date' => NULL,
											'last_days' => NULL,
											'sort_key' => 2,
											'std' => 16,
											'cxp_use_type' => 0,
											'cxxp_use_type' => 0,
											'cxp' => NULL,
											'cxxp' => NULL,
											'study' =>
												array(
													'chara_magic_study_id' => 17,
													'name' => '派手にいきましょう！',
													'description' => '成功率が4％上がります。\\n\\n必要素材数が15％増えます。\\n\\n研究魔法Lvが上がると、\\n効果が上がります。',
													'magic' =>
														array(
														),
													'lv' => 1,
												),
											'event_sp_info' => NULL,
										),
									17 =>
										array(
											'sys_id' => 18,
											'res_id' => 'SNOW',
											'atlas_res_id' =>
												array(
													'0' => NULL,
													'2' => NULL,
													'3' => NULL,
													'4' => NULL,
													'5' => NULL,
													'6' => NULL,
												),
											'name' => 'スノウ',
											'clickable' => true,
											'mhp' => 200,
											'hp' => NULL,
											'lv' => 10,
											'unlocked' => false,
											'progress' => 0,
											'expired' => false,
											'open_date' => NULL,
											'close_date' => NULL,
											'last_days' => NULL,
											'sort_key' => 2,
											'std' => 17,
											'cxp_use_type' => 0,
											'cxxp_use_type' => 0,
											'cxp' => NULL,
											'cxxp' => NULL,
											'study' =>
												array(
													'chara_magic_study_id' => 18,
													'name' => '上手くできるといいです',
													'description' => '完成するまでの時間が\\n15％短くなります。\\n\\n研究魔法Lvが上がると、\\n効果が上がります。',
													'magic' =>
														array(
														),
													'lv' => 1,
												),
											'event_sp_info' => NULL,
										),
									18 =>
										array(
											'sys_id' => 19,
											'res_id' => 'LUCE',
											'atlas_res_id' =>
												array(
													'0' => NULL,
													'2' => NULL,
													'3' => NULL,
													'4' => NULL,
													'5' => NULL,
													'6' => NULL,
												),
											'name' => 'ルーチェ',
											'clickable' => true,
											'mhp' => 200,
											'hp' => NULL,
											'lv' => 10,
											'unlocked' => false,
											'progress' => 0,
											'expired' => false,
											'open_date' => NULL,
											'close_date' => NULL,
											'last_days' => NULL,
											'sort_key' => 2,
											'std' => 18,
											'cxp_use_type' => 0,
											'cxxp_use_type' => 0,
											'cxp' => NULL,
											'cxxp' => NULL,
											'study' =>
												array(
													'chara_magic_study_id' => 19,
													'name' => '楽しんで作るのがコツです',
													'description' => '成功率が4％上がります。\\n\\n失敗時の素材返還数が15％減ります。\\n\\n研究魔法Lvが上がると、\\n効果が上がります。',
													'magic' =>
														array(
														),
													'lv' => 1,
												),
											'event_sp_info' => NULL,
										),
									19 =>
										array(
											'sys_id' => 20,
											'res_id' => 'VICTORIA',
											'atlas_res_id' =>
												array(
													'0' => NULL,
													'2' => NULL,
													'3' => NULL,
													'4' => NULL,
													'5' => NULL,
													'6' => NULL,
													'7' => NULL,
												),
											'name' => 'ヴィクトリア',
											'clickable' => true,
											'mhp' => 200,
											'hp' => NULL,
											'lv' => 10,
											'unlocked' => false,
											'progress' => 0,
											'expired' => false,
											'open_date' => NULL,
											'close_date' => NULL,
											'last_days' => NULL,
											'sort_key' => 2,
											'std' => 15,
											'cxp_use_type' => 0,
											'cxxp_use_type' => 0,
											'cxp' => NULL,
											'cxxp' => NULL,
											'study' =>
												array(
													'chara_magic_study_id' => 20,
													'name' => '凄いものを作るの！',
													'description' => 'ルーンスロットが1％付きやすく\\nなります。\\n\\n成功率が20％下がります。\\n\\n研究魔法Lvが上がると、\\n効果が上がります。',
													'magic' =>
														array(
														),
													'lv' => 1,
												),
											'event_sp_info' => NULL,
										),
									20 =>
										array(
											'sys_id' => 21,
											'res_id' => 'HILDA',
											'atlas_res_id' =>
												array(
													'0' => NULL,
													'2' => NULL,
													'3' => NULL,
													'4' => NULL,
													'5' => NULL,
													'6' => NULL,
												),
											'name' => 'ヒルダ',
											'clickable' => true,
											'mhp' => 200,
											'hp' => NULL,
											'lv' => 10,
											'unlocked' => false,
											'progress' => 0,
											'expired' => false,
											'open_date' => NULL,
											'close_date' => NULL,
											'last_days' => NULL,
											'sort_key' => 2,
											'std' => 13,
											'cxp_use_type' => 0,
											'cxxp_use_type' => 0,
											'cxp' => NULL,
											'cxxp' => NULL,
											'study' =>
												array(
													'chara_magic_study_id' => 21,
													'name' => 'よくわからない……',
													'description' => '失敗時の素材返還数が15％増えます。\\n\\nルーンスロットが1％付きにくく\\nなります。\\n\\n研究魔法Lvが上がると、\\n効果が上がります。',
													'magic' =>
														array(
														),
													'lv' => 1,
												),
											'event_sp_info' => NULL,
										),
									21 =>
										array(
											'sys_id' => 22,
											'res_id' => 'FLORIA',
											'atlas_res_id' =>
												array(
													'0' => NULL,
													'2' => NULL,
													'3' => NULL,
													'4' => NULL,
												),
											'name' => 'フロリア',
											'clickable' => true,
											'mhp' => 200,
											'hp' => NULL,
											'lv' => 10,
											'unlocked' => false,
											'progress' => 0,
											'expired' => false,
											'open_date' => NULL,
											'close_date' => NULL,
											'last_days' => NULL,
											'sort_key' => 2,
											'std' => 19,
											'cxp_use_type' => 0,
											'cxxp_use_type' => 0,
											'cxp' => NULL,
											'cxxp' => NULL,
											'study' =>
												array(
													'chara_magic_study_id' => 22,
													'name' => 'じっくり育ててあげましょう',
													'description' => 'プラス補正が1％付きやすくなります。\\n\\n必要素材数が15％増えます。\\n\\n完成するまでの時間が\\n15％長くなります。\\n\\n研究魔法Lvが上がると、\\n効果が上がります。',
													'magic' =>
														array(
														),
													'lv' => 1,
												),
											'event_sp_info' => NULL,
										),
									22 =>
										array(
											'sys_id' => 23,
											'res_id' => 'CARUL',
											'atlas_res_id' =>
												array(
													'0' => NULL,
													'2' => NULL,
													'3' => NULL,
												),
											'name' => 'キャルル',
											'clickable' => true,
											'mhp' => 200,
											'hp' => NULL,
											'lv' => 10,
											'unlocked' => false,
											'progress' => 0,
											'expired' => false,
											'open_date' => NULL,
											'close_date' => NULL,
											'last_days' => NULL,
											'sort_key' => 2,
											'std' => 18,
											'cxp_use_type' => 0,
											'cxxp_use_type' => 0,
											'cxp' => NULL,
											'cxxp' => NULL,
											'study' =>
												array(
													'chara_magic_study_id' => 23,
													'name' => 'あたしに任せて！',
													'description' => 'ルーンスロットが1％付きやすく\\nなります。\\n\\n必要素材数が15％増えます。\\n\\n完成するまでの時間が\\n15％長くなります。\\n\\n研究魔法Lvが上がると、\\n効果が上がります。',
													'magic' =>
														array(
														),
													'lv' => 1,
												),
											'event_sp_info' => NULL,
										),
									23 =>
										array(
											'sys_id' => 24,
											'res_id' => 'CHACO',
											'atlas_res_id' =>
												array(
													'0' => NULL,
													'2' => 'CHACOARTIST',
													'3' => NULL,
													'4' => 'CHACOCOWBOY',
												),
											'name' => 'チャコ',
											'clickable' => true,
											'mhp' => 200,
											'hp' => NULL,
											'lv' => 10,
											'unlocked' => false,
											'progress' => 0,
											'expired' => false,
											'open_date' => NULL,
											'close_date' => NULL,
											'last_days' => NULL,
											'sort_key' => 2,
											'std' => 15,
											'cxp_use_type' => 0,
											'cxxp_use_type' => 0,
											'cxp' => NULL,
											'cxxp' => NULL,
											'study' =>
												array(
													'chara_magic_study_id' => 24,
													'name' => '手早く済ませたい',
													'description' => '完成するまでの時間が\\n15％短くなります。\\n\\n失敗時の素材返還数が15％減ります。\\n\\n研究魔法Lvが上がると、\\n効果が上がります。',
													'magic' =>
														array(
														),
													'lv' => 1,
												),
											'event_sp_info' => NULL,
										),
									24 =>
										array(
											'sys_id' => 25,
											'res_id' => 'COSETTE',
											'atlas_res_id' =>
												array(
													'0' => NULL,
													'3' => NULL,
													'4' => NULL,
												),
											'name' => 'コゼット',
											'clickable' => true,
											'mhp' => 200,
											'hp' => NULL,
											'lv' => 10,
											'unlocked' => false,
											'progress' => 0,
											'expired' => false,
											'open_date' => NULL,
											'close_date' => NULL,
											'last_days' => NULL,
											'sort_key' => 2,
											'std' => 15,
											'cxp_use_type' => 0,
											'cxxp_use_type' => 0,
											'cxp' => NULL,
											'cxxp' => NULL,
											'study' =>
												array(
													'chara_magic_study_id' => 25,
													'name' => 'これならきっと大丈夫',
													'description' => '成功率が4％上がります。\\n\\n研究魔法Lvが上がると、\\n効果が上がります。',
													'magic' =>
														array(
														),
													'lv' => 1,
												),
											'event_sp_info' => NULL,
										),
									25 =>
										array(
											'sys_id' => 26,
											'res_id' => 'ULLR',
											'atlas_res_id' =>
												array(
													'0' => NULL,
													'3' => NULL,
												),
											'name' => 'ウル',
											'clickable' => true,
											'mhp' => 200,
											'hp' => NULL,
											'lv' => 10,
											'unlocked' => false,
											'progress' => 0,
											'expired' => false,
											'open_date' => NULL,
											'close_date' => NULL,
											'last_days' => NULL,
											'sort_key' => 2,
											'std' => 16,
											'cxp_use_type' => 0,
											'cxxp_use_type' => 0,
											'cxp' => NULL,
											'cxxp' => NULL,
											'study' =>
												array(
													'chara_magic_study_id' => 26,
													'name' => '本気見せちゃいますよ！',
													'description' => 'プラス補正が1％付きやすくなります。\\n\\n成功率が20％下がります。\\n\\n研究魔法Lvが上がると、\\n効果が上がります。',
													'magic' =>
														array(
														),
													'lv' => 1,
												),
											'event_sp_info' => NULL,
										),
									26 =>
										array(
											'sys_id' => 27,
											'res_id' => 'SYLVIA',
											'atlas_res_id' =>
												array(
													'0' => NULL,
													'2' => NULL,
													'3' => NULL,
													'4' => NULL,
													'5' => NULL,
												),
											'name' => 'シルビア',
											'clickable' => true,
											'mhp' => 200,
											'hp' => NULL,
											'lv' => 10,
											'unlocked' => false,
											'progress' => 0,
											'expired' => false,
											'open_date' => NULL,
											'close_date' => NULL,
											'last_days' => NULL,
											'sort_key' => 2,
											'std' => 19,
											'cxp_use_type' => 0,
											'cxxp_use_type' => 0,
											'cxp' => NULL,
											'cxxp' => NULL,
											'study' =>
												array(
													'chara_magic_study_id' => 27,
													'name' => '私にかかればこうですよ',
													'description' => 'プラス補正が1％付きやすくなります。\\n\\nルーンスロットが1％付きにくく\\nなります。\\n\\n必要素材数が15％増えます。\\n\\n研究魔法Lvが上がると、\\n効果が上がります。',
													'magic' =>
														array(
														),
													'lv' => 1,
												),
											'event_sp_info' => NULL,
										),
									27 =>
										array(
											'sys_id' => 28,
											'res_id' => 'REN',
											'atlas_res_id' =>
												array(
													'0' => NULL,
													'3' => NULL,
													'4' => NULL,
												),
											'name' => 'レン',
											'clickable' => true,
											'mhp' => 200,
											'hp' => NULL,
											'lv' => 10,
											'unlocked' => false,
											'progress' => 0,
											'expired' => false,
											'open_date' => NULL,
											'close_date' => NULL,
											'last_days' => NULL,
											'sort_key' => 2,
											'std' => 14,
											'cxp_use_type' => 0,
											'cxxp_use_type' => 0,
											'cxp' => NULL,
											'cxxp' => NULL,
											'study' =>
												array(
													'chara_magic_study_id' => 28,
													'name' => 'アタシの裏技見せてあげる！',
													'description' => '完成するまでの時間が\\n15％短くなります。\\n\\n研究魔法Lvが上がると、\\n効果が上がります。',
													'magic' =>
														array(
														),
													'lv' => 1,
												),
											'event_sp_info' => NULL,
										),
									28 =>
										array(
											'sys_id' => 29,
											'res_id' => 'MAY',
											'atlas_res_id' =>
												array(
													'0' => NULL,
													'2' => NULL,
													'3' => NULL,
													'4' => NULL,
													'5' => NULL,
												),
											'name' => 'メイ',
											'clickable' => true,
											'mhp' => 200,
											'hp' => NULL,
											'lv' => 10,
											'unlocked' => false,
											'progress' => 0,
											'expired' => false,
											'open_date' => NULL,
											'close_date' => NULL,
											'last_days' => NULL,
											'sort_key' => 2,
											'std' => 16,
											'cxp_use_type' => 0,
											'cxxp_use_type' => 0,
											'cxp' => NULL,
											'cxxp' => NULL,
											'study' =>
												array(
													'chara_magic_study_id' => 29,
													'name' => 'たぶんきっと大丈夫だとおもいたい！',
													'description' => '完成するまでの時間が\\n15％短くなります。\\n\\n失敗時の素材返還数が15％減ります。\\n\\n研究魔法Lvが上がると、\\n効果が上がります。',
													'magic' =>
														array(
														),
													'lv' => 1,
												),
											'event_sp_info' => NULL,
										),
									29 =>
										array(
											'sys_id' => 30,
											'res_id' => 'NALU',
											'atlas_res_id' =>
												array(
													'0' => NULL,
													'2' => NULL,
													'3' => NULL,
												),
											'name' => 'ナル',
											'clickable' => true,
											'mhp' => 200,
											'hp' => NULL,
											'lv' => 10,
											'unlocked' => false,
											'progress' => 0,
											'expired' => false,
											'open_date' => NULL,
											'close_date' => NULL,
											'last_days' => NULL,
											'sort_key' => 2,
											'std' => 14,
											'cxp_use_type' => 0,
											'cxxp_use_type' => 0,
											'cxp' => NULL,
											'cxxp' => NULL,
											'study' =>
												array(
													'chara_magic_study_id' => 30,
													'name' => '過去は振り返らない',
													'description' => '失敗時の素材返還数が15％増えます。\\n\\nルーンスロットが1％付きにくく\\nなります。\\n\\n研究魔法Lvが上がると、\\n効果が上がります。',
													'magic' =>
														array(
														),
													'lv' => 1,
												),
											'event_sp_info' => NULL,
										),
									30 =>
										array(
											'sys_id' => 31,
											'res_id' => 'ELISE',
											'atlas_res_id' =>
												array(
													'0' => NULL,
													'3' => NULL,
													'4' => NULL,
												),
											'name' => 'エリーゼ',
											'clickable' => true,
											'mhp' => 200,
											'hp' => NULL,
											'lv' => 10,
											'unlocked' => false,
											'progress' => 0,
											'expired' => false,
											'open_date' => NULL,
											'close_date' => NULL,
											'last_days' => NULL,
											'sort_key' => 2,
											'std' => 19,
											'cxp_use_type' => 0,
											'cxxp_use_type' => 0,
											'cxp' => NULL,
											'cxxp' => NULL,
											'study' =>
												array(
													'chara_magic_study_id' => 31,
													'name' => '逸品のために至上の音楽を',
													'description' => '必要素材数が15％減ります。\\n\\n完成するまでの時間が15%長くなります。\\n研究魔法Lvが上がると、効果が上がります。',
													'magic' =>
														array(
														),
													'lv' => 1,
												),
											'event_sp_info' => NULL,
										),
									31 =>
										array(
											'sys_id' => 32,
											'res_id' => 'YUNI',
											'atlas_res_id' =>
												array(
													'0' => NULL,
													'3' => NULL,
													'4' => NULL,
												),
											'name' => 'ユーニ',
											'clickable' => true,
											'mhp' => 200,
											'hp' => NULL,
											'lv' => 10,
											'unlocked' => false,
											'progress' => 0,
											'expired' => false,
											'open_date' => NULL,
											'close_date' => NULL,
											'last_days' => NULL,
											'sort_key' => 2,
											'std' => 18,
											'cxp_use_type' => 0,
											'cxxp_use_type' => 0,
											'cxp' => NULL,
											'cxxp' => NULL,
											'study' =>
												array(
													'chara_magic_study_id' => 32,
													'name' => '果報は寝て待て！',
													'description' => '完成するまでの時間が15%長くなります。\\nプラス補正が1%付きやすくなります。\\n研究魔法Lvが上がると、効果が上がります。',
													'magic' =>
														array(
														),
													'lv' => 1,
												),
											'event_sp_info' => NULL,
										),
									32 =>
										array(
											'sys_id' => 33,
											'res_id' => 'MIKOTO',
											'atlas_res_id' =>
												array(
													'0' => NULL,
													'2' => NULL,
													'3' => NULL,
													'4' => NULL,
												),
											'name' => 'ミコト',
											'clickable' => true,
											'mhp' => 200,
											'hp' => NULL,
											'lv' => 10,
											'unlocked' => false,
											'progress' => 0,
											'expired' => false,
											'open_date' => NULL,
											'close_date' => NULL,
											'last_days' => NULL,
											'sort_key' => 2,
											'std' => 18,
											'cxp_use_type' => 0,
											'cxxp_use_type' => 0,
											'cxp' => NULL,
											'cxxp' => NULL,
											'study' =>
												array(
													'chara_magic_study_id' => 33,
													'name' => '心静かに参りましょう',
													'description' => 'ルーンスロットが1％付きやすく\\nなります。\\n\\n成功率が20％下がります。\\n\\n研究魔法Lvが上がると、\\n効果が上がります。',
													'magic' =>
														array(
														),
													'lv' => 1,
												),
											'event_sp_info' => NULL,
										),
									33 =>
										array(
											'sys_id' => 34,
											'res_id' => 'FREDERICA',
											'atlas_res_id' =>
												array(
													'0' => NULL,
													'2' => NULL,
													'3' => NULL,
													'4' => NULL,
												),
											'name' => 'フレデリカ',
											'clickable' => true,
											'mhp' => 200,
											'hp' => NULL,
											'lv' => 10,
											'unlocked' => false,
											'progress' => 0,
											'expired' => false,
											'open_date' => NULL,
											'close_date' => NULL,
											'last_days' => NULL,
											'sort_key' => 2,
											'std' => 15,
											'cxp_use_type' => 0,
											'cxxp_use_type' => 0,
											'cxp' => NULL,
											'cxxp' => NULL,
											'study' =>
												array(
													'chara_magic_study_id' => 34,
													'name' => '要らぬ世話かもしれないが',
													'description' => 'ルーンスロットが1％付きやすくなります。\\n必要素材数が15％増えます。\\n完成するまでの時間が15%長くなります。\\n研究魔法Lvが上がると、効果が上がります。',
													'magic' =>
														array(
														),
													'lv' => 1,
												),
											'event_sp_info' => NULL,
										),
									34 =>
										array(
											'sys_id' => 35,
											'res_id' => 'SOPHIE',
											'atlas_res_id' =>
												array(
													'0' => NULL,
													'2' => NULL,
													'3' => NULL,
												),
											'name' => 'ソフィー',
											'clickable' => true,
											'mhp' => 200,
											'hp' => NULL,
											'lv' => 10,
											'unlocked' => false,
											'progress' => 0,
											'expired' => false,
											'open_date' => NULL,
											'close_date' => NULL,
											'last_days' => NULL,
											'sort_key' => 2,
											'std' => 18,
											'cxp_use_type' => 0,
											'cxxp_use_type' => 0,
											'cxp' => NULL,
											'cxxp' => NULL,
											'study' =>
												array(
													'chara_magic_study_id' => 35,
													'name' => '祈りましょう！',
													'description' => '成功率が4％上がります。\\nルーンスロットが1％付きやすくなります。\\n失敗時の素材返還数が15％減ります。\\n研究魔法Lvが上がると、効果が上がります。',
													'magic' =>
														array(
														),
													'lv' => 1,
												),
											'event_sp_info' => NULL,
										),
									35 =>
										array(
											'sys_id' => 36,
											'res_id' => 'COCO',
											'atlas_res_id' =>
												array(
													'0' => NULL,
													'2' => NULL,
													'3' => NULL,
												),
											'name' => 'ココ',
											'clickable' => true,
											'mhp' => 200,
											'hp' => NULL,
											'lv' => 10,
											'unlocked' => false,
											'progress' => 0,
											'expired' => false,
											'open_date' => NULL,
											'close_date' => NULL,
											'last_days' => NULL,
											'sort_key' => 2,
											'std' => 14,
											'cxp_use_type' => 0,
											'cxxp_use_type' => 0,
											'cxp' => NULL,
											'cxxp' => NULL,
											'study' =>
												array(
													'chara_magic_study_id' => 36,
													'name' => 'なんでもできるもん！',
													'description' => 'プラス補正が1％付きやすくなります。\\n10％の確率で失敗をキャンセルして成功にします。\\n失敗時の素材返還数が15％減ります。\\n研究魔法Lvが上がると、効果が上がります。',
													'magic' =>
														array(
														),
													'lv' => 1,
												),
											'event_sp_info' => NULL,
										),
									36 =>
										array(
											'sys_id' => 37,
											'res_id' => 'CHIROL',
											'atlas_res_id' =>
												array(
													'0' => NULL,
													'2' => NULL,
													'3' => NULL,
												),
											'name' => 'チロル',
											'clickable' => true,
											'mhp' => 200,
											'hp' => NULL,
											'lv' => 10,
											'unlocked' => false,
											'progress' => 0,
											'expired' => false,
											'open_date' => NULL,
											'close_date' => NULL,
											'last_days' => NULL,
											'sort_key' => 2,
											'std' => 21,
											'cxp_use_type' => 0,
											'cxxp_use_type' => 0,
											'cxp' => NULL,
											'cxxp' => NULL,
											'study' =>
												array(
													'chara_magic_study_id' => 37,
													'name' => 'チロルにお任せ！',
													'description' => '成功率が4％上がります。\\nルーンスロットが1％付きやすくなります。\\n失敗時の素材返還数が15％減ります。\\n研究魔法Lvが上がると、効果が上がります。',
													'magic' =>
														array(
														),
													'lv' => 1,
												),
											'event_sp_info' => NULL,
										),
									37 =>
										array(
											'sys_id' => 800,
											'res_id' => 'FRFRICHIGO',
											'atlas_res_id' =>
												array(
													0 => NULL,
												),
											'name' => 'いちご',
											'clickable' => true,
											'mhp' => 200,
											'hp' => NULL,
											'lv' => 40,
											'unlocked' => false,
											'progress' => 0,
											'expired' => true,
											'open_date' => '2020-09-24 13:00:00',
											'close_date' => '2021-07-15 12:59:59',
											'last_days' => -137,
											'sort_key' => 3,
											'std' => 40,
											'cxp_use_type' => 0,
											'cxxp_use_type' => 0,
											'cxp' => NULL,
											'cxxp' => NULL,
											'study' =>
												array(
													'chara_magic_study_id' => 800,
													'name' => '今、ビビッときたわ',
													'description' => '成功率が4％上がります。\\nプラス補正が1％付きにくくなります。\\n\\n研究魔法Lvが上がると、効果が上がります。',
													'magic' =>
														array(
														),
													'lv' => 1,
												),
											'event_sp_info' => NULL,
										),
									38 =>
										array(
											'sys_id' => 801,
											'res_id' => 'FRFRBUDOU',
											'atlas_res_id' =>
												array(
													0 => NULL,
												),
											'name' => 'ぶどう',
											'clickable' => true,
											'mhp' => 200,
											'hp' => NULL,
											'lv' => 40,
											'unlocked' => false,
											'progress' => 0,
											'expired' => true,
											'open_date' => '2020-09-17 13:00:00',
											'close_date' => '2021-07-15 12:59:59',
											'last_days' => -137,
											'sort_key' => 3,
											'std' => 51,
											'cxp_use_type' => 0,
											'cxxp_use_type' => 0,
											'cxp' => NULL,
											'cxxp' => NULL,
											'study' =>
												array(
													'chara_magic_study_id' => 801,
													'name' => 'うん、いい感じ♪',
													'description' => 'ルーンスロットが1％付きやすくなります。\\n完成するまでの時間が15％長くなります。\\n\\n研究魔法Lvが上がると、効果が上がります。',
													'magic' =>
														array(
														),
													'lv' => 1,
												),
											'event_sp_info' => NULL,
										),
									39 =>
										array(
											'sys_id' => 802,
											'res_id' => 'FRFRMIKAN',
											'atlas_res_id' =>
												array(
													0 => NULL,
												),
											'name' => 'みかん',
											'clickable' => true,
											'mhp' => 200,
											'hp' => NULL,
											'lv' => 10,
											'unlocked' => false,
											'progress' => 0,
											'expired' => true,
											'open_date' => '2020-09-17 13:00:00',
											'close_date' => '2021-07-15 12:59:59',
											'last_days' => -137,
											'sort_key' => 3,
											'std' => 14,
											'cxp_use_type' => 0,
											'cxxp_use_type' => 0,
											'cxp' => NULL,
											'cxxp' => NULL,
											'study' =>
												array(
													'chara_magic_study_id' => 802,
													'name' => 'ぽぽいのぽい！',
													'description' => '完成するまでの時間が15%短くなります。\\n失敗時の素材返還数が15％減ります。\\n\\n研究魔法Lvが上がると、効果が上がります。',
													'magic' =>
														array(
														),
													'lv' => 1,
												),
											'event_sp_info' => NULL,
										),
									40 =>
										array(
											'sys_id' => 803,
											'res_id' => 'IOLITEMARIRU',
											'atlas_res_id' =>
												array(
													0 => NULL,
												),
											'name' => 'マリル',
											'clickable' => true,
											'mhp' => 200,
											'hp' => NULL,
											'lv' => 40,
											'unlocked' => false,
											'progress' => 0,
											'expired' => true,
											'open_date' => '2021-08-26 13:00:00',
											'close_date' => '2021-09-09 12:59:59',
											'last_days' => -81,
											'sort_key' => 3,
											'std' => 40,
											'cxp_use_type' => 0,
											'cxxp_use_type' => 0,
											'cxp' => NULL,
											'cxxp' => NULL,
											'study' =>
												array(
													'chara_magic_study_id' => 803,
													'name' => 'これでどうかな…？',
													'description' => '完成するまでの時間が15%短くなります。\\n失敗時の素材返還数が15％減ります。\\n\\n研究魔法Lvが上がると、効果が上がります。',
													'magic' =>
														array(
														),
													'lv' => 1,
												),
											'event_sp_info' => NULL,
										),
									41 =>
										array(
											'sys_id' => 804,
											'res_id' => 'IOLITEUMEW',
											'atlas_res_id' =>
												array(
													0 => NULL,
												),
											'name' => 'ウミュウ',
											'clickable' => true,
											'mhp' => 200,
											'hp' => NULL,
											'lv' => 40,
											'unlocked' => false,
											'progress' => 0,
											'expired' => true,
											'open_date' => '2021-09-02 13:00:00',
											'close_date' => '2021-09-16 12:59:59',
											'last_days' => -74,
											'sort_key' => 3,
											'std' => 53,
											'cxp_use_type' => 0,
											'cxxp_use_type' => 0,
											'cxp' => NULL,
											'cxxp' => NULL,
											'study' =>
												array(
													'chara_magic_study_id' => 804,
													'name' => 'ちょっと本気出します',
													'description' => '成功率が4％上がります。\\nプラス補正が1％付きにくくなります。\\n\\n研究魔法Lvが上がると、効果が上がります。',
													'magic' =>
														array(
														),
													'lv' => 1,
												),
											'event_sp_info' => NULL,
										),
									42 =>
										array(
											'sys_id' => 805,
											'res_id' => 'IOLITELILENDA',
											'atlas_res_id' =>
												array(
													0 => NULL,
												),
											'name' => 'リレンダ',
											'clickable' => true,
											'mhp' => 200,
											'hp' => NULL,
											'lv' => 10,
											'unlocked' => false,
											'progress' => 0,
											'expired' => true,
											'open_date' => '2021-08-26 13:00:00',
											'close_date' => '2021-09-09 12:59:59',
											'last_days' => -81,
											'sort_key' => 3,
											'std' => 16,
											'cxp_use_type' => 0,
											'cxxp_use_type' => 0,
											'cxp' => NULL,
											'cxxp' => NULL,
											'study' =>
												array(
													'chara_magic_study_id' => 805,
													'name' => '天啓が下りましたわ',
													'description' => 'ルーンスロットが1％付きやすくなります。\\n完成するまでの時間が15％長くなります。\\n\\n研究魔法Lvが上がると、効果が上がります。',
													'magic' =>
														array(
														),
													'lv' => 1,
												),
											'event_sp_info' => NULL,
										),
								),
						),
				),
		);

		return $this->responseJson($array);
	}

	#[Annotation\Route(method: 'POST', pattern: '/quest/getGalleryList')]
	public function getGalleryList(Request $request)
	{
		$this->setDbInstance();
		$query = $this->dbInstance->query("SELECT * FROM quest_h_story WHERE story_type = 'h_scene'");
		$query->execute();
		$hStories = $this->dbInstance->fetch($query, 'all');

		$hStories = new ArrayObject($hStories);

		$query = $this->dbInstance->query("SELECT * FROM quest_h_story WHERE story_type = 'episode' OR story_type = 'h_episode_sp'");
		$query->execute();
		$advStories = $this->dbInstance->fetch($query, 'all');

		$advStories = new ArrayObject($advStories);

		$query = $this->dbInstance->query("SELECT * FROM quest_h_story WHERE story_type = 'episode' OR story_type = 'ruins'");
		$query->execute();
		$ruinsStories = $this->dbInstance->fetch($query, 'all');

		$ruinsStories = new ArrayObject($ruinsStories);

		$hStories = $hStories->map(function ($data) {
			if (!isset($data['clickable'])) {
				return $data;
			}

			$data['clickable'] = $data['clickable'] == 1;

			return $data;
		});

		$stories = array(
			'head' =>
				array(
					'user_data' =>
						array(
							'user_id' => $this->userId,
							'platform_id' => '24066034',
							'platform_name' => 'せくすせく',
							'platform_type' => 'r18',
							'hero_name' => '',
							'hero_name_id' => NULL,
							'user_lv' => 26,
							'user_exp' => 0,
							'ap' => 1418,
							'ap_updated' => '2021-11-29 00:08:00',
							'ep' => 48,
							'ep_updated' => '2021-11-29 00:08:00',
							'cxp' => 0,
							'cxxp' => 0,
							'favorite_chara_id' => 'ROLA',
							'current_board_index' => 0,
							'max_board_count' => 3,
							'last_modified_date' => '2021-11-29 01:20:50',
							'prestart_sp_date' => NULL,
							'days_count' => 0,
							'login_count' => 0,
							'tutorial_active' => 'Y',
							'debug_flag' => 'N',
							'status' => 1,
							'enable' => 'Y',
							'created' => '2018-10-30 01:35:41',
							'updated' => '2021-11-29 01:20:50',
							'ruins_unlock_key' => 0,
							'update_info' =>
								array(
									'login_bonus' => false,
									'notice' => false,
									'adv_notice' => true,
									'mission' => false,
								),
							'badge' =>
								array(
									'new_chara' => false,
									'new_ep' => false,
									'creation_completed' => false,
									'gacha_ticket_normal' => '650',
									'gacha_ticket_special' => 10,
									'present_count' => '232',
									'cxp' => '524',
									'cxxp' => '16',
									'good_count' => 0,
								),
						),
					'server' =>
						array(
							'timestamp' => 1638116863,
							'date' => '2021-11-29 01:27:43',
							'error_level' => 0,
							'error_page_id' => 0,
							'error_message' => '',
						),
				),
			'body' =>
				array(
					'gallery' =>
						array(
							'h_story' =>
								array(
									'sys_id' => 'h_main',
									'unread_cnt' => 0,
									'clickable' => true,
									'detail' => $hStories,
								),
							'episode' =>
								array(
									'sys_id' => 'h_main',
									'unread_cnt' => 0,
									'clickable' => true,
									'detail' => $hStories,
								),
							'adv' =>
								array(
									'sys_id' => 'adv',
									'res_id' => NULL,
									'clickable' => true,
									'detail' => $advStories,
								),
							'ruins' =>
								array(
									'sys_id' => 'ruins',
									'res_id' => NULL,
									'unread_cnt' => 0,
									'clickable' => true,
									'detail' => $ruinsStories,
								),
						),
				),
		);

		return $this->responseText(json_encode($stories));
	}

	#[Annotation\Route(method: 'POST', pattern: '/quest/getHsceneList')]
	public function getHsceneList(Request $request)
	{
		$this->setDbInstance();
		$query = $this->dbInstance->query("SELECT * FROM quest_h_story WHERE story_type = 'h_scene'");
		$query->execute();
		$hStories = $this->dbInstance->fetch($query, 'all');

		$stories = array(
			'head' =>
				array(
					'user_data' =>
						array(
							'user_id' => $this->userId,
							'platform_id' => '24066034',
							'platform_name' => 'せくすせく',
							'platform_type' => 'r18',
							'hero_name' => '',
							'hero_name_id' => NULL,
							'user_lv' => 26,
							'user_exp' => 0,
							'ap' => 1418,
							'ap_updated' => '2021-11-29 00:08:00',
							'ep' => 48,
							'ep_updated' => '2021-11-29 00:08:00',
							'cxp' => 0,
							'cxxp' => 0,
							'favorite_chara_id' => 'ROLA',
							'current_board_index' => 0,
							'max_board_count' => 3,
							'last_modified_date' => '2021-11-29 01:20:50',
							'prestart_sp_date' => NULL,
							'days_count' => 0,
							'login_count' => 0,
							'tutorial_active' => 'Y',
							'debug_flag' => 'N',
							'status' => 1,
							'enable' => 'Y',
							'created' => '2018-10-30 01:35:41',
							'updated' => '2021-11-29 01:20:50',
							'ruins_unlock_key' => 0,
							'update_info' =>
								array(
									'login_bonus' => false,
									'notice' => false,
									'adv_notice' => true,
									'mission' => false,
								),
							'badge' =>
								array(
									'new_chara' => false,
									'new_ep' => false,
									'creation_completed' => false,
									'gacha_ticket_normal' => '650',
									'gacha_ticket_special' => 10,
									'present_count' => '232',
									'cxp' => '524',
									'cxxp' => '16',
									'good_count' => 0,
								),
						),
					'server' =>
						array(
							'timestamp' => 1638116940,
							'date' => '2021-11-29 01:29:00',
							'error_level' => 0,
							'error_page_id' => 0,
							'error_message' => '',
						),
				),
			'body' =>
				array(
					'quest' =>
						array(
							'h_story' => $hStories
						),
				),
		);

		return $this->responseText(json_encode($stories));
	}

	#[Annotation\Route(method: 'POST', pattern: '/quest/viewStory')]
	public function viewStory(Request $request)
	{
		$array = array(
			'head' =>
				array(
					'user_data' =>
						array(
							'user_id' => $this->userId,
							'platform_id' => '24066034',
							'platform_name' => 'せくすせく',
							'platform_type' => 'r18',
							'hero_name' => '',
							'hero_name_id' => NULL,
							'user_lv' => 26,
							'user_exp' => 0,
							'ap' => 1418,
							'ap_updated' => '2021-11-29 00:08:00',
							'ep' => 48,
							'ep_updated' => '2021-11-29 00:08:00',
							'cxp' => 0,
							'cxxp' => 0,
							'favorite_chara_id' => 'ROLA',
							'current_board_index' => 0,
							'max_board_count' => 3,
							'last_modified_date' => '2021-11-29 01:20:50',
							'prestart_sp_date' => NULL,
							'days_count' => 0,
							'login_count' => 0,
							'tutorial_active' => 'Y',
							'debug_flag' => 'N',
							'status' => 1,
							'enable' => 'Y',
							'created' => '2018-10-30 01:35:41',
							'updated' => '2021-11-29 01:20:50',
							'ruins_unlock_key' => 0,
							'update_info' =>
								array(
									'login_bonus' => false,
									'notice' => false,
									'adv_notice' => true,
									'mission' => false,
								),
							'badge' =>
								array(
									'new_chara' => false,
									'new_ep' => false,
									'creation_completed' => false,
									'gacha_ticket_normal' => '650',
									'gacha_ticket_special' => 10,
									'present_count' => '232',
									'cxp' => '524',
									'cxxp' => '16',
									'good_count' => 0,
								),
						),
					'server' =>
						array(
							'timestamp' => 1638117009,
							'date' => '2021-11-29 01:30:09',
							'error_level' => 0,
							'error_page_id' => 0,
							'error_message' => '',
						),
				),
			'body' =>
				array(
					'quest' =>
						array(
							'unlock_story' => NULL,
							'next_story' => NULL,
							'reward_item' => NULL,
						),
				),
		);

		return $this->responseJson($array);
	}

	#[Annotation\Route(method: 'POST', pattern: '/notice/getNoticeList')]
	public function getNoticeList(Request $request)
	{
		$contents = json_encode([
			'head' => [
				'user_data' => [],
				'server' => [
					'timestamp' => 1638116379
				]
			]
		]);

		return $this->responseText($contents);
	}

}