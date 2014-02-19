<?php
/**
 * @link http://dragonjsonserver.de/
 * @copyright Copyright (c) 2012-2014 DragonProjects (http://dragonprojects.de/)
 * @license http://license.dragonprojects.de/dragonjsonserver.txt New BSD License
 * @author Christoph Herrmann <developer@dragonprojects.de>
 * @package DragonJsonServerAlliance
 */

namespace DragonJsonServerAlliance\Event;

/**
 * Eventklasse für die Erstellung einer Allianzbeziehung
 */
class CreateAllianceavatar extends \Zend\EventManager\Event
{
	/**
	 * @var string
	 */
	protected $name = 'CreateAllianceavatar';

    /**
     * Setzt die Allianz der Allianzbeziehung der erstellt wurde
     * @param \DragonJsonServerAlliance\Entity\Alliance $alliance
     * @return CreateAllianceavatar
     */
    public function setAlliance(\DragonJsonServerAlliance\Entity\Alliance $alliance)
    {
        $this->setParam('alliance', $alliance);
        return $this;
    }

    /**
     * Gibt die Allianz der Allianzbeziehungs der erstellt wurde zurück
     * @return \DragonJsonServerAlliance\Entity\Alliance
     */
    public function getAlliance()
    {
        return $this->getParam('alliance');
    }

    /**
     * Setzt die Allianzbeziehung der erstellt wurde
     * @param \DragonJsonServerAlliance\Entity\Allianceavatar $allianceavatar
     * @return CreateAllianceavatar
     */
    public function setAllianceavatar(\DragonJsonServerAlliance\Entity\Allianceavatar $allianceavatar)
    {
        $this->setParam('allianceavatar', $allianceavatar);
        return $this;
    }

    /**
     * Gibt die Allianzbeziehung der erstellt wurde zurück
     * @return \DragonJsonServerAlliance\Entity\Allianceavatar
     */
    public function getAllianceavatar()
    {
        return $this->getParam('allianceavatar');
    }
}
