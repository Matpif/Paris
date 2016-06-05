<?php

/**
 * Created by PhpStorm.
 * User: matpif
 * Date: 05/06/16
 * Time: 10:55
 */
class MonClassementController extends Controller
{
    function __construct()
    {
        parent::__construct();
        $this->_url = '/MonClassement';
        $this->_title = 'Mon classement par poule';
        $this->_page = 'MonClassement';
        $this->setTemplate('MonClassement.phtml');
    }

    /**
     * @param $pouleId int
     * @return EquipeCollection
     */
    public function getEquipesOrder($pouleId) {
        $_equipeCollection = new EquipeCollection();
        $_equipeCollection->loadByQuery('
                    SELECT equipe.*,
                                CASE WHEN pari.id IS NULL THEN 0 
                                    WHEN  equipe.id = match.equipe_id_1 AND pari.score_equipe_1 > pari.score_equipe_2 THEN 3
                                    WHEN  equipe.id = match.equipe_id_1 AND pari.score_equipe_1 = pari.score_equipe_2 THEN 1
                                    WHEN equipe.id = match.equipe_id_1 AND pari.score_equipe_1 < pari.score_equipe_2 THEN 0
                                    WHEN  equipe.id = match.equipe_id_2 AND pari.score_equipe_2 > pari.score_equipe_1 THEN 3
                                    WHEN  equipe.id = match.equipe_id_2 AND pari.score_equipe_2 = pari.score_equipe_1 THEN 1
                                    WHEN equipe.id = match.equipe_id_2 AND pari.score_equipe_2 < pari.score_equipe_1 THEN 0
                                    ELSE 0
                                END as score
                    FROM equipe
                    INNER JOIN match
                    ON match.equipe_id_1 = equipe.id
                        OR match.equipe_id_2 = equipe.id
                        OR match.flag_phase_finale <> 1
                    LEFT JOIN pari
                    ON pari.match_id = match.id
                        AND pari.utilisateur_id = :utilisateur_id
                    WHERE equipe.poule_id = :poule_id
                    GROUP BY equipe.id, equipe.name, equipe.image, equipe.poule_id
                    ORDER BY score DESC', ['poule_id' => $pouleId, 'utilisateur_id' => Access::getInstance()->getCurrentUser()->getAttribute('id')]);

        return $_equipeCollection;
    }

    /**
     * @return PouleCollection
     */
    public function getAllPoule() {
        return (new PouleCollection())->loadAll('name');
    }
}