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
	 * Setzt die ID der Allianzbeziehung
	 * @param integer $allianceavatar_id
	 * @return Allianceavatar
	 */
	protected function setAllianceavatarId($allianceavatar_id)
	{
		$this->allianceavatar_id = $allianceavatar_id;
		return $this;
	}
	
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
	 * Setzt die Attribute der Allianzbeziehung aus dem Array
	 * @param array $array
	 * @return Allianceavatar
	 */
	public function fromArray(array $array)
	{
		return $this
			->setAllianceavatarId($array['allianceavatar_id'])
			->setModifiedTimestamp($array['modified'])
			->setCreatedTimestamp($array['created'])
			->setAvatar((new \DragonJsonServerAvatar\Entity\Avatar())->fromArray($array['avatar']))
			->setAllianceId($array['alliance_id'])
			->setRole($array['role']);
	}
	
	/**
	 * Gibt die Attribute der Allianzbeziehung als Array zurück
	 * @return array
	 */
	public function toArray()
	{
		return [
			'__className' => __CLASS__,
			'allianceavatar_id' => $this->getAllianceavatarId(),
			'modified' => $this->getModifiedTimestamp(),
			'created' => $this->getCreatedTimestamp(),
			'avatar' => $this->getAvatar()->toArray(),
			'alliance_id' => $this->getAllianceId(),
			'role' => $this->getRole(),
		];
	}
}
