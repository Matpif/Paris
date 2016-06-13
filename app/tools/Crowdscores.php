<?php

/**
 * Created by PhpStorm.
 * User: matpif
 * Date: 13/06/16
 * Time: 19:17
 */
class Crowdscores
{
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
            $_match = $this->sendRequest('matchs', [
                'competition_id' => $this->competition_id
                , 'api_key' => $this->api_key
                , 'from' => (new DateTime($match->getAttribute('date')))->format(DATE_ATOM)
                , 'to' => (new DateTime($match->getAttribute('date')))->add(new DateInterval('P1D'))->format(DATE_ATOM)
                , 'team_id' => $equipe_1->getAttribute('crowdscores_id')
            ]);

            $retour['score_equipe_1'] = $match['homeGoals'];
            $retour['score_equipe_2'] = $match['awayGoals'];
        }

        return $retour;
    }

    /**
     * @param string $function
     * @param array | null $attribute
     * @return array | null
     */
    private function sendRequest($function, $attribute = null) {

        $r = new HttpRequest('https://api.crowdscores.com/api/v1/'.$function, HttpRequest::METH_GET);
        if (is_array($attribute))
            $r->addQueryData($attribute);

        try {
            $r->send();
            if ($r->getResponseCode() == 200) {
                return json_decode($r->getResponseBody());
            }
        } catch (HttpException $ex) {
        }

        return null;
    }
}