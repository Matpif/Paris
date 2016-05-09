<?php

/**
 * Created by PhpStorm.
 * User: matpif
 * Date: 29/04/16
 * Time: 15:45
 */
class MatchsController extends Controller
{
    /**
     * @var PariCollection
     */
    private $_pariCollection;

    function __construct()
    {
        parent::__construct();
        $this->_url = '/Matchs';
        $this->setTemplate('/matchs.phtml');
        $this->_title = 'Les Matchs';
        $this->_page = 'Matchs';
    }

    public function testAction () {
        $collection = new MatchCollection();
        $collection->loadAll('date');

        /** @var MatchModel $match */
        foreach ($collection as $match) {
            $equipe_1 = (new EquipeCollection())->loadById($match->getAttribute('equipe_id_1'));
            $equipe_2 = (new EquipeCollection())->loadById($match->getAttribute('equipe_id_2'));

            echo 'Date : ' . $match->getAttribute('date') . '<br/>';
            echo '<img width="35px" src="/images/pays/' . $equipe_1->getAttribute('image') . '" />' . $equipe_1->getAttribute('name') . ' - ' . $equipe_2->getAttribute('name') . '<img width="35px" src="/images/pays/' . $equipe_2->getAttribute('image') . '" />';
            echo '<br/><br/>';
        }
        die;
    }

    /**
     * @return MatchCollection
     */
    public function getAllMatch() {
        $matchCollection = new MatchCollection();
        return $matchCollection->loadAll(["date" => Collection::SORT_ASC]);
    }

    /**
     * @param MatchModel $match
     * @return null | PariModel
     */
    public function getPari($match) {

        $_utilisateur = Access::getInstance()->getCurrentUser();
        if ($this->_pariCollection == null) {
            $this->_pariCollection = new PariCollection();
        }

        return $this->_pariCollection->load(array("utilisateur_id" => $_utilisateur->getAttribute('utilisateur_id'), "match_id" => $match->getAttribute('id')))->getFirstRow();
    }
}