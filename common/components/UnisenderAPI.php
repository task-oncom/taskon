<?php
namespace common\components;
use common\modules\triggers\components\conditions\vendor\ConditionBase;
use yii\helpers\Json;

/**
 * API UniSender
 *
 * @see http://www.unisender.com/ru/help/api/
 * @version 1.3
 */
class UnisenderAPI {
	/**
	 * @var string
	 */
	protected $ApiKey;

	/**
	 * @var string
	 */
	protected $Encoding = 'UTF8';

	/**
	 * @var int
	 */
	protected $RetryCount = 0;

	/**
	 * @var float
	 */
	protected $Timeout;

	/**
	 * @var bool
	 */
	protected $Compression = false;

    /**
     * @var array
     */
    protected $visitedLinks = [];

    /**
     * @var array
     */
    protected $statuses = [];

    /**
     * @param string $ApiKey
     * @param string $Encoding
     * @param int $RetryCount
     * @param null $Timeout
     * @param bool $Compression
     */
    function __construct($ApiKey = '5p7mt1be5x6axqniwu937gqohj9k9hn7gbex1efo', $Encoding = 'UTF8', $RetryCount = 4, $Timeout = null, $Compression = false) {
		$this->ApiKey = $ApiKey;

		if (!empty($Encoding)) {
			$this->Encoding = $Encoding;
		}

		if (!empty($RetryCount)) {
			$this->RetryCount = $RetryCount;
		}

		if (!empty($Timeout)) {
			$this->Timeout = $Timeout;
		}

		if ($Compression) {
			$this->Compression = $Compression;
		}
	}

	/**
	 * @param string $Name
	 * @param array $Arguments
	 * @return string
	 */
	function __call($Name, $Arguments) {
		if (!is_array($Arguments) || empty($Arguments)) {
			$Params = array();
		} else {
			$Params = $Arguments[0];
		}

		return $this->callMethod($Name, $Params);
	}

    public function createList($title=null){
        if ($title===null) {
            $date = new \DateTime;
            $title = $date->format('YmdHis').(rand(100000, 999999));
        }
        return $this->callMethod(
            'createList',
            [
                'title' => $title
            ]
        );
    }

	/**
	 * @param $campaign_id
	 * @param $message_id
	 * @param $email
	 * @return array|mixed
	 */
    public function getMessageStatuses($campaign_id, $message_id, $email){
        $result = $this->callMethod(
            'getCampaignDeliveryStats',
            [
                'campaign_id' => $campaign_id
            ]
        );
		$result = Json::decode($result);
		if (array_key_exists('result', $result) && array_key_exists('data', $result['result']) && array_key_exists('letter_id', $result['result']) && $result['result']['letter_id']==$message_id) {
			foreach($result['result']['data'] as $data) {
				$this->statuses[] = $data[1];
			}
		}
        return $this->statuses;
    }

    /**
     * @return bool
     */
    public function isLinkVisited(){
		foreach($this->statuses as $status) {
			if ($status==ConditionBase::MESSAGE_LINK_VISITED || $status==ConditionBase::MESSAGE_UNSUBSCRIBED || $status==ConditionBase::MESSAGE_SPAM_FOLDER)
				return true;
		}
        return false;
    }

    /**
     * @return bool
     */
    public function isUnsubscribed(){
		foreach($this->statuses as $status) {
			if ($status==ConditionBase::MESSAGE_UNSUBSCRIBED)
				return true;
		}
        return false;
    }

    /**
     * @return bool
     */
    public function isReaded(){
		foreach($this->statuses as $status) {
			if ($status==ConditionBase::MESSAGE_READ || $status==ConditionBase::MESSAGE_SPAM_FOLDER || $status==ConditionBase::MESSAGE_UNSUBSCRIBED)
				return true;
		}
        return false;
    }

    /**
     * @return bool
     */
    public function isDelivered(){
		foreach($this->statuses as $status) {
			if ($status==ConditionBase::MESSAGE_DELIVERED || $status==ConditionBase::MESSAGE_SPAM_FOLDER || $status==ConditionBase::MESSAGE_LINK_VISITED || $status==ConditionBase::MESSAGE_UNSUBSCRIBED || $status==ConditionBase::MESSAGE_READ)
				return true;
		}
        return false;
    }

    /**
     * @param $campaign_id
     * @return mixed
     */
    public function getVisitedLinks($campaign_id){
        $this->visitedLinks = $this->callMethod(
            'getVisitedLinks',
            [
                'campaign_id' => $campaign_id
            ]
        );
        $this->visitedLinks = Json::decode($this->visitedLinks);
        return $this->visitedLinks;
    }

    /**
     * @param $email
     * @param $link
     * @return bool
     */
    public function linkIsVisited($email, $link) {
        if (array_key_exists('result', $this->visitedLinks) && array_key_exists('data', $this->visitedLinks['result'])) {
            foreach($this->visitedLinks['result']['data'] as $transition) {
//                if (strtolower($transition[0])==strtolower($email) && strpos($link, $transition[1])!==false)
//                    return true;
                if (strtolower($transition[0])==strtolower($email)) {
					if (strlen($link)>strlen($transition[1])) {
						if (strpos($link, $transition[1])!==false)
							return true;
					} elseif (strlen($link)>strlen($transition[1])) {
						if (strpos($transition[1], $link)!==false)
							return true;
					} else {
						if (strpos($link, $transition[1])!==false)
							return true;
					}
				}
            }
        }
        return false;
    }

    public function createCampaign($message_id){
        return $this->callMethod(
            'createCampaign',
            [
                'message_id' => $message_id,
                'defer' => 1,
                'track_links' => 1,
                'track_read' => 1
            ]
        );
    }

    public function getLists(){
        return $this->callMethod(
            'getLists'
        );
    }

    public function getContactCount($listId, $params){
        $params=(array)$params;
        $params['list_id'] = $listId;
        return $this->callMethod(
            'getContactCount',
            $params
        );
    }

    public function createEmailMessage($sender_name, $sender_email, $subject, $body, $list_id){
        return $this->callMethod(
            'createEmailMessage',
            [
                'sender_name' => $sender_name,
                'sender_email' => $sender_email,
                'subject' => $subject,
                'body' => $body,
                'list_id' => $list_id
            ]
        );
    }

    /**
     * @param $email
     * @return array
     */
    public function validateSender($email){
        return $this->callMethod(
            'validateSender',
            [
                'email' => $email
            ]
        );
    }

	/**
	 * @param array $Params
	 * @return string
	 */
	function subscribe($Params) {
		$Params = (array)$Params;

		if (empty($Params['request_ip'])) {
			$Params['request_ip'] = $this->getClientIp();
		}

		return $this->callMethod('subscribe', $Params);
	}

	/**
	 * @param string $JSON
	 * @return mixed
	 */
	protected function decodeJSON($JSON) {
		return json_decode($JSON);
	}

	/**
	 * @return string
	 */
	protected function getClientIp() {
		$Result = '';

		if (!empty($_SERVER["REMOTE_ADDR"])) {
			$Result = $_SERVER["REMOTE_ADDR"];
		} else if (!empty($_SERVER["HTTP_X_FORWARDED_FOR"]))  {
			$Result = $_SERVER["HTTP_X_FORWARDED_FOR"];
		} else if (!empty($_SERVER["HTTP_CLIENT_IP"])) {
			$Result = $_SERVER["HTTP_CLIENT_IP"];
		}

		if (preg_match('/([0-9]|[0-9][0-9]|[01][0-9][0-9]|2[0-4][0-9]|25[0-5])(\.([0-9]|[0-9][0-9]|[01][0-9][0-9]|2[0-4][0-9]|25[0-5])){3}/', $Result, $Match)) {
			return $Match[0];
		}

		return $Result;
	}

	/**
	 * @param string $Value
	 * @param string $Key
	 */
	protected function iconv(&$Value, $Key) {
		$Value = iconv($this->Encoding, 'UTF8//IGNORE', $Value);
	}

	/**
	 * @param string $Value
	 * @param string $Key
	 */
	protected function mb_convert_encoding(&$Value, $Key) {
		$Value = mb_convert_encoding($Value, 'UTF8', $this->Encoding);
	}

	/**
	 * @param string $MethodName
	 * @param array $Params
	 * @return array
	 */
	protected function callMethod($MethodName, $Params = array()) {
		if ($this->Encoding != 'UTF8') {
			if (function_exists('iconv')) {
				array_walk_recursive($Params, array($this, 'iconv'));
			} else if (function_exists('mb_convert_encoding')) {
				array_walk_recursive($Params, array($this, 'mb_convert_encoding'));
			}
		}

		$Url = $MethodName . '?format=json';

		if ($this->Compression) {
			$Url .= '&api_key=' . $this->ApiKey . '&request_compression=bzip2';
			$Content = bzcompress(http_build_query($Params));
		} else {
			$Params = array_merge((array)$Params, array('api_key' => $this->ApiKey));
			$Content = http_build_query($Params);
		}

		$ContextOptions = array(
			'http' => array(
				'method'  => 'POST',
				'header'  => 'Content-type: application/x-www-form-urlencoded',
				'content' => $Content,
			)
		);

		if ($this->Timeout) {
			$ContextOptions['http']['timeout'] = $this->Timeout;
		}

		$RetryCount = 0;
		$Context = stream_context_create($ContextOptions);

		do {
			$Host = $this->getApiHost($RetryCount);
			$Result = file_get_contents($Host . $Url, false, $Context);
			$RetryCount++;
		} while ($Result === false && $RetryCount < $this->RetryCount);

		return $Result;
	}

	/**
	 * @param int $RetryCount
	 * @return string
	 */
	protected function getApiHost($RetryCount = 0) {
		if ($RetryCount % 2 == 0) {
			return 'http://api.unisender.com/ru/api/';
		} else {
			return 'http://www.api.unisender.com/ru/api/';
		}
	}
}