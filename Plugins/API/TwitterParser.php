<?php

include('./vendor/autoload.php');

use Xanax\Classes\ClientURL as ClientURL;

class TwitterParser
{

	private $guestToken;
	private $token = "AAAAAAAAAAAAAAAAAAAAANRILgAAAAAAnNwIzUejRCOuH5E6I8xnZz4puTs%3D1Zv7ttfk8LF81IUq16cHjhLTvJu4FA33AGWWjCpTnA";
	private $activateUrl;
	private $videoUrl;
	private $graphqlUrl = "";

	public function __construct()
	{
		$this->guestToken = $this->getGuestToken();
		$this->activateUrl = "https://api.twitter.com/1.1/guest/activate.json";
		$this->videoUrl = "https://twitter.com/i/api/2/timeline/conversation/%s.json?include_profile_interstitial_type=1&include_blocking=1&include_blocked_by=1&include_followed_by=1&include_want_retweets=1&include_mute_edge=1&include_can_dm=1&include_can_media_tag=1&skip_status=1&cards_platform=Web-12&include_cards=1&include_ext_alt_text=true&include_quote_count=true&include_reply_count=1&tweet_mode=extended&include_entities=true&include_user_entities=true&include_ext_media_color=true&include_ext_media_availability=true&send_error_codes=true&simple_quoted_tweet=true&count=50&include_ext_has_birdwatch_notes=false&ext=mediaStats%2ChighlightedLabel";
		$this->graphqlUrl = "https://twitter.com/i/api/graphql/2Kp5fEiA-6QtZoCKRCcGKg/UserTweetsAndReplies?variables=%7B%22userId%22%3A%2%s%22%2C%22count%22%3A20%2C%22withHighlightedLabel%22%3Atrue%2C%22withTweetQuoteCount%22%3Atrue%2C%22includePromotedContent%22%3Atrue%2C%22withTweetResult%22%3Afalse%2C%22withReactions%22%3Afalse%2C%22withUserResults%22%3Afalse%2C%22withVoice%22%3Afalse%2C%22withNonLegacyCard%22%3Atrue%2C%22withBirdwatchPivots%22%3Afalse%7D";
	}

	public function getGuestToken()
	{
		$headers = array(
			'authorization:Bearer ' . $this->token
		);

		$cURL = new ClientURL();
		$cURL->option->setURL($this->activateUrl)
			->setPostField(true)
			->setHeaders($headers)
			->setReturnTransfer(true)
			->setAutoReferer(true)
			->setReturnHeader(false);

		$result = $cURL->execute();
		$guestToken = json_decode($result)->guest_token;

		return $guestToken;
	}

	public function getVideoUrl($id)
	{
		$videoUrl = sprintf($this->videoUrl, $id);

		return $videoUrl;
	}

	public function getUserTweetsAndReplies($userId, $guestToken)
	{
		$headers = array(
			'authorization:Bearer ' . $this->token,
			'x-guest-token:' . $guestToken
		);

		$requestUrl = sprintf($this->graphqlUrl, $userId);

		$cURL = new ClientURL();
		$cURL->option->setURL($requestUrl)
			->setPostMethod(false)
			->setHeaders($headers)
			->setReturnTransfer(true)
			->setAutoReferer(true)
			->setReturnHeader(false);

		$result = $cURL->execute();
		return json_decode($result)->data->user->result;
	}
}
