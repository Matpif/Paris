<?php

/**
 * Created by PhpStorm.
 * User: matpif
 * Date: 07/06/16
 * Time: 18:58
 */

class NotifMatch implements Cron
{
    public function _run()
    {
        $cs = new Crowdscores();
        $matchCollection = (new MatchCollection())->load([
            "date" => ["<", date('Y-m-d H:i:s', time() - 5400)]
            , 'score_equipe_1' => [' IS ', null], 'score_equipe_2' => [' IS ', null]
        ]);

        /** @var MatchModel $match */
        foreach ($matchCollection as $match) {
            $score = $cs->getScore($match);
            if ($score['score_equipe_1'] != null && $score['score_equipe_2'] != null) {
                $match->setAttribute('score_equipe_1', $score['score_equipe_1']);
                $match->setAttribute('score_equipe_2', $score['score_equipe_2']);
                $match->save();
            }
        }
    }
}