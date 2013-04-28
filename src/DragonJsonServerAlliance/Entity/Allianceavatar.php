<?php
/**
 * @link http://dragonjsonserver.de/
 * @copyright Copyright (c) 2012-2013 DragonProjects (http://dragonprojects.de/)
 * @license http://license.dragonprojects.de/dragonjsonserver.txt New BSD License
 * @author Christoph Herrmann <developer@dragonprojects.de>
 * @package DragonJsonServerAlliance
 */

namespace DragonJsonServerAlliance\Entity;

/**
 * Entityklasse einer Allianz
 * @Doctrine\ORM\Mapping\Entity
 * @Doctrine\ORM\Mapping\Table(name="allianceavatars")
 */
class Allianceavatar
{
	use \DragonJsonServerDoctrine\Entity\ModifiedTrait;
	use \DragonJsonServerDoctrine\Entity\CreatedTrait;
	use \DragonJsonServerAvatar\Entity\AvatarTrait;
	use \DragonJsonServerAlliance\Entity\AllianceIdTrait;
	
	/**
	 * @Doctrine\ORM\Mapping\Id 
	 * @Doctrine\ORM\Mapping\Column(type="integer")
	 * @Doctrine\ORM\Mapping\GeneratedValue
	 **/
	protected $allianceavatar_id;
	
	/**
	 * @Doctrine\ORM\Mapping\Column(type="string")
	 **/
	protected $role;
	
	/**
	 * Gibt die ID der Allianzbeziehung zurück
	 * @return integer
	 */
	public function getAllianceavatarId()
	{
		return $this->allianceavatar_id;
	}
	
	/**
	 * Setzt die Rolle der Allianzbeziehung
	 * @param string $tag
	 * @return Allianceavatar
	 */
	public function setRole($role)
	{
		$this->role = $role;
		return $this;
	}
	
	/**
	 * Gibt die Rolle der Allianzbeziehung zurück
	 * @return string
	 */
	public function getRole()
	{
		return $this->role;
	}
	
	/**
	 * Gibt die Attribute des Avatars als Array zurück
	 * @return array
	 */
	public function toArray()
	{
		return [
			'allianceavatar_id' => $this->getAllianceavatarId(),
			'modified' => $this->getModifiedTimestamp(),
			'created' => $this->getCreatedTimestamp(),
			'avatar' => $this->getAvatar()->toArray(),
			'alliance_id' => $this->getAllianceId(),
			'role' => $this->getRole(),
		];
	}
}
