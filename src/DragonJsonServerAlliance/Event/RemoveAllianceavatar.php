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
 * Eventklasse für die Entfernung einer Allianzbeziehung
 */
class RemoveAllianceavatar extends \Zend\EventManager\Event
{
	/**
	 * @var string
	 */
	protected $name = 'removeallianceavatar';

    /**
     * Setzt die Allianzbeziehung der entfernt wird
     * @param \DragonJsonServerAlliance\Entity\Allianceavatar $allianceavatar
     * @return RemoveAllianceavatar
     */
    public function setAllianceavatar(\DragonJsonServerAlliance\Entity\Allianceavatar $allianceavatar)
    {
        $this->setParam('allianceavatar', $allianceavatar);
        return $this;
    }

    /**
     * Gibt die Allianzbeziehung der entfernt wird zurück
     * @return \DragonJsonServerAlliance\Entity\Allianceavatar
     */
    public function getAllianceavatar()
    {
        return $this->getParam('allianceavatar');
    }
}
