<?php
/**
 * @link http://dragonjsonserver.de/
 * @copyright Copyright (c) 2012-2013 DragonProjects (http://dragonprojects.de/)
 * @license http://license.dragonprojects.de/dragonjsonserver.txt New BSD License
 * @author Christoph Herrmann <developer@dragonprojects.de>
 * @package DragonJsonServerAlliance
 */

namespace DragonJsonServerAlliance\Event;

/**
 * Eventklasse für die Änderung der Rolle einer Allianzbeziehung
 */
class ChangeRole extends \Zend\EventManager\Event
{
	/**
	 * @var string
	 */
	protected $name = 'ChangeRole';

    /**
     * Setzt die Allianzbeziehung dessen Rolle aktualisiert wird
     * @param \DragonJsonServerAlliance\Entity\Allianceavatar $allianceavatar
     * @return ChangeRole
     */
    public function setAllianceavatar(\DragonJsonServerAlliance\Entity\Allianceavatar $allianceavatar)
    {
        $this->setParam('allianceavatar', $allianceavatar);
        return $this;
    }

    /**
     * Gibt die Allianzbeziehung dessen Rolle aktualisiert wird zurück
     * @return \DragonJsonServerAlliance\Entity\Allianceavatar
     */
    public function getAllianceavatar()
    {
        return $this->getParam('allianceavatar');
    }

    /**
     * Setzt die Rolle die gesetzt wird
     * @param string $role
     * @return ChangeRole
     */
    public function setRole($role)
    {
        $this->setParam('role', $role);
        return $this;
    }

    /**
     * Gibt die Rolle die gesetzt wird zurück
     * @return string
     */
    public function getRole()
    {
        return $this->getParam('role');
    }
}
