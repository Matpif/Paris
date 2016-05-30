<?php

/**
 * Created by PhpStorm.
 * User: matpif
 * Date: 30/05/16
 * Time: 19:29
 */
class SendMail
{
    const PATH_TEMPLATE = '../view/template/mail/';
    /**
     * Contient l'expéditeur
     * @var String
     */
    private $expediteur;

    /**
     * Contient le destinataire
     * @var String
     */
    private $destinataire;

    /**
     * Contient l'objet du mail
     * @var String
     */
    private $objet;

    /**
     * Contient le contenu du mail
     * @var String
     */
    private $message;

    /**
     * Fichiers attachés
     * @var String
     */
    private $attachement;

    private $limite;

    /**
     * Instancie l'objet EnvoiMail
     */
    public function __construct(){
        $this->limite = "----=_parties_".md5(uniqid (rand()));
    }

    /**
     * Retourne le destination du mail
     * @return String
     */
    private function getDestinataire(){
        return $this->destinataire;
    }

    /**
     * Retourne l'expéditeur du mail
     * @return String
     */
    private function getExpediteur(){
        return $this->expediteur;
    }

    /**
     * Retourne l'objet du mail
     * @return String
     */
    private function getObjet(){
        return $this->objet;
    }

    /**
     * Retourne le contenu du mail
     * @return String
     */
    private function getMessage(){
        return "--".$this->limite."\n".'Content-type: text/html; charset=utf-8'."\n\n".$this->message."\n\n";
    }

    public function addAttachments($path) {

        $this->attachement .= "--".$this->limite."\n";
        $this->attachement .= "Content-Type: ".mime_content_type($path)."; name=".basename($path)."\n";
        $this->attachement .= "Content-Transfer-Encoding: base64\n";
        $this->attachement .= "Content-Disposition: attachment; filename=".basename($path)."\n\n";

        $fd = fopen( $path, "r" );
        $contenu = fread( $fd, filesize( $path ) );
        fclose( $fd );
        $this->attachement .= chunk_split(base64_encode($contenu));
    }


    /**
     * Définition de l'expéditeur
     * @param type $expediteur
     */
    public function setExpediteur($expediteur) {
        $this->expediteur = $expediteur;
    }

    /**
     * Définition du destinataire
     * @param type $destinataire
     */
    public function setDestinataire($destinataire) {
        $this->destinataire = $destinataire;
    }


    /**
     * Définition de l'objet du mail
     * @param string $objet
     */
    public function setObjet($objet) {
        $this->objet = $objet;
    }

    /**
     * Définition du message
     * @param type $message
     */
    public function setMessage($message) {
        $this->message = $message;
    }

    /**
     * Envoi le mail
     * @return boolean
     */
    public function envoi(){
        $headers  = 'MIME-Version: 1.0' . "\n";
        $headers .= 'From: '.ReadIni::getInstance()->getAttribute('mail', 'from')."\n";
        $headers .= 'Reply-To: '.ReadIni::getInstance()->getAttribute('mail', 'reply_to')."\n";

        $headers .= "Content-type: multipart/alternative; boundary=\"".$this->limite."\"\n";
        return mail($this->getDestinataire(),
            utf8_decode($this->getObjet()),
            $this->getMessage().$this->attachement,
            $headers);
    }
    
    public function setTemplate($template, $params = null) {

        if (file_exists(self::PATH_TEMPLATE.$template)){
            ob_start();
            include(self::PATH_TEMPLATE.$template);
            $this->setMessage(ob_get_contents());
            ob_end_clean();
        }
    }
}
