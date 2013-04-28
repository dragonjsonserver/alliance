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
 * Eventklasse für die Entfernung einer ALlianz
 */
class RemoveAlliance extends \Zend\EventManager\Event
{
	/**
	 * @var string
	 */
	protected $name = 'removealliance';

    /**
     * Setzt die Allianz die entfernt wird
     * @param \DragonJsonServerAlliance\Entity\Alliance $alliance
     * @return RemoveAlliance
     */
    public function setAlliance(\DragonJsonServerAlliance\Entity\Alliance $alliance)
    {
        $this->setParam('alliance', $alliance);
        return $this;
    }

    /**
     * Gibt die Allianz die entfernt wird zurück
     * @return \DragonJsonServerAlliance\Entity\Alliance
     */
    public function getAlliance()
    {
        return $this->getParam('alliance');
    }
}
