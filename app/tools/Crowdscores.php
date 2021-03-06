<?php

/**
 * Created by PhpStorm.
 * User: matpif
 * Date: 13/06/16
 * Time: 19:17
 */
class Crowdscores
{

    const URL = "https://api.crowdscores.com/api/v1/";
    /**
     * @var string
     */
    private $api_key;
    /**
     * @var string
     */
    private $competition_id;

    function __construct()
    {
        $this->api_key = ReadIni::getInstance()->getAttribute('crowdscores', 'api_key');
        $this->competition_id = ReadIni::getInstance()->getAttribute('crowdscores', 'competition_id');
    }

    /**
     * @return bool
     */
    public function isActive() {
        return (trim($this->api_key) != '');
    }

    /**
     * @param MatchModel $match
     * @return array
     */
    public function getScore($match) {

        /** @var EquipeModel $equipe_1 */
        $equipe_1 = $match->getEquipe_1();

        $retour = [];
        if ($equipe_1->getAttribute('crowdscores_id')) {
            $_match = $this->sendRequest('matches', [
                'competition_id' => $this->competition_id
                , 'api_key' => $this->api_key
                , 'from' => (new DateTime($match->getAttribute('date')))->format(DATE_ATOM)
                , 'to' => (new DateTime($match->getAttribute('date')))->add(new DateInterval('P1D'))->format(DATE_ATOM)
                , 'team_id' => $equipe_1->getAttribute('crowdscores_id')
            ]);
            
            $retour['score_equipe_1'] = $_match[0]['homeGoals'];
            $retour['score_equipe_2'] = $_match[0]['awayGoals'];
            $retour['score_tir_but_1'] = (isset($_match[0]['penaltyShootout']['score']['home']))?$_match[0]['penaltyShootout']['score']['home']:null;
            $retour['score_tir_but_2'] = (isset($_match[0]['penaltyShootout']['score']['away']))?$_match[0]['penaltyShootout']['score']['away']:null;
            $retour['is_finish'] = is_array($_match[0]['outcome'])?1:0;
        }

        return $retour;
    }

    /**
     * @param string $function
     * @param array | null $attribute
     * @return array | null
     */
    private function sendRequest($function, $attribute = null) {

        $retour = null;
        $url = self::URL.$function;
        if (is_array($attribute)) {

            $i = 0;
            foreach ($attribute as $key => $value) {
                if ($i == 0)
                    $url .= '?';
                $url .= $key.'='.$value.'&';
                $i++;
            }
            $url = substr($url, 0, -1);
        }
        
        $http = curl_init($url);
        curl_setopt($http, CURLOPT_RETURNTRANSFER,1);
        $response = curl_exec($http);

        $header_size = curl_getinfo($http, CURLINFO_HEADER_SIZE);
        $result['http_code'] = curl_getinfo($http, CURLINFO_HTTP_CODE);

        if ($result['http_code'] == '200') {
            $retour = json_decode($response, true);
        }

        curl_close($http);

        return $retour;
    }
}