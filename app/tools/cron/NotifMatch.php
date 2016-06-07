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
        $_paramsMail = array();
        $_matchCollection = (new MatchCollection())->load(['date' => ['<', date('Y-m-d H:i:s', time() - 86400)]]);
        /** @var MatchModel $match */
        foreach ($_matchCollection as $match) {
            $_paramsMail[] = $match;
        }

        $_utilisateurCollection = new UtilisateurCollection();
        $_utilisateurCollection->loadByQuery('SELECT Utilisateur.*
                                    FROM Utilisateur
                                    WHERE Utilisateur.id NOT IN (
                                        SELECT Utilisateur.id
                                        FROM Utilisateur
                                        INNER JOIN pari
                                            ON pari.utilisateur_id = Utilisateur.id
                                        INNER JOIN match
                                            ON match.id = pari.match_id
                                            AND match.date <  datetime(\'now\',\'+1 day\')
                                            AND match.date >  datetime(\'now\')
                                    ) AND Utilisateur.privilege <> 9;');

        /** @var UtilisateurModel $_utilisateur */
        foreach ($_utilisateurCollection as $_utilisateur) {
            
            $_sendMail = new SendMail();
            $_sendMail->setDestinataire($_utilisateur->getAttribute('email'));
            $_sendMail->setTemplate('notifMatch.phtml', $_paramsMail);
            $_sendMail->envoi();
        }

    }
}