<?php
/**
 * @link http://dragonjsonserver.de/
 * @copyright Copyright (c) 2012-2014 DragonProjects (http://dragonprojects.de/)
 * @license http://license.dragonprojects.de/dragonjsonserver.txt New BSD License
 * @author Christoph Herrmann <developer@dragonprojects.de>
 * @package DragonJsonServerAlliance
 */

namespace DragonJsonServerAlliance\Entity;

/**
 * Trait für die AllianceID mit der Beziehung zu einer Allianz
 */
trait AllianceIdTrait
{
	/**
	 * @Doctrine\ORM\Mapping\Column(type="integer")
	 **/
	protected $alliance_id;
	
	/**
	 * Setzt die AllianceID der Entity
	 * @param integer $alliance_id
	 * @return AllianceIdTrait
	 */
	public function setAllianceId($alliance_id)
	{
		$this->alliance_id = $alliance_id;
		return $this;
	}
	
	/**
	 * Gibt die AllianceID der Entity zurück
	 * @return integer
	 */
	public function getAllianceId()
	{
		return $this->alliance_id;
	}
}
