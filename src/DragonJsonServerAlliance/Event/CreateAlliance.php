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
 * Eventklasse für die Erstellung einer ALlianz
 */
class CreateAlliance extends \Zend\EventManager\Event
{
	/**
	 * @var string
	 */
	protected $name = 'CreateAlliance';

    /**
     * Setzt die Allianz die erstellt wurde
     * @param \DragonJsonServerAlliance\Entity\Alliance $alliance
     * @return CreateAlliance
     */
    public function setAlliance(\DragonJsonServerAlliance\Entity\Alliance $alliance)
    {
        $this->setParam('alliance', $alliance);
        return $this;
    }

    /**
     * Gibt die Allianz die erstellt wurde zurück
     * @return \DragonJsonServerAlliance\Entity\Alliance
     */
    public function getAlliance()
    {
        return $this->getParam('alliance');
    }

    /**
     * Setzt die Allianzbeziehung der Allianz die erstellt wurde
     * @param \DragonJsonServerAlliance\Entity\Allianceavatar $allianceavatar
     * @return CreateAllianceavatar
     */
    public function setAllianceavatar(\DragonJsonServerAlliance\Entity\Allianceavatar $allianceavatar)
    {
        $this->setParam('allianceavatar', $allianceavatar);
        return $this;
    }

    /**
     * Gibt die Allianzbeziehung der Allianz die erstellt wurde zurück
     * @return \DragonJsonServerAlliance\Entity\Allianceavatar
     */
    public function getAllianceavatar()
    {
        return $this->getParam('allianceavatar');
    }
}
