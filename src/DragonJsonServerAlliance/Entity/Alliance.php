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
 * @Doctrine\ORM\Mapping\Table(name="alliances")
 */
class Alliance
{
	use \DragonJsonServerDoctrine\Entity\ModifiedTrait;
	use \DragonJsonServerDoctrine\Entity\CreatedTrait;
	use \DragonJsonServerGameround\Entity\GameroundIdTrait;
	
	/**
	 * @Doctrine\ORM\Mapping\Id 
	 * @Doctrine\ORM\Mapping\Column(type="integer")
	 * @Doctrine\ORM\Mapping\GeneratedValue
	 **/
	protected $alliance_id;
	
	/**
	 * @Doctrine\ORM\Mapping\Column(type="string")
	 **/
	protected $tag;
	
	/**
	 * @Doctrine\ORM\Mapping\Column(type="string")
	 **/
	protected $name;
	
	/**
	 * @Doctrine\ORM\Mapping\Column(type="string")
	 **/
	protected $description;
	
	/**
	 * Setzt die ID der Allianz
	 * @param integer $alliance_id
	 * @return Alliance
	 */
	protected function setAllianceId($alliance_id)
	{
		$this->alliance_id = $alliance_id;
		return $this;
	}
	
	/**
	 * Gibt die ID der Allianz zurück
	 * @return integer
	 */
	public function getAllianceId()
	{
		return $this->alliance_id;
	}
	
	/**
	 * Setzt den Tag der Allianz
	 * @param string $tag
	 * @return Alliance
	 */
	public function setTag($tag)
	{
		$this->tag = $tag;
		return $this;
	}
	
	/**
	 * Gibt den Tag der Allianz zurück
	 * @return string
	 */
	public function getTag()
	{
		return $this->tag;
	}
	
	/**
	 * Setzt den Namen der Allianz
	 * @param string $name
	 * @return Alliance
	 */
	public function setName($name)
	{
		$this->name = $name;
		return $this;
	}
	
	/**
	 * Gibt den Namen der Allianz zurück
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}
	
	/**
	 * Setzt die Beschreibung der Allianz
	 * @param string $description
	 * @return Alliance
	 */
	public function setDescription($description)
	{
		$this->description = $description;
		return $this;
	}
	
	/**
	 * Gibt die Beschreibung der Allianz zurück
	 * @return string
	 */
	public function getDescription()
	{
		return $this->description;
	}
	
	/**
	 * Setzt die Attribute der Allianz aus dem Array
	 * @param array $array
	 * @return Alliance
	 */
	public function fromArray(array $array)
	{
		return $this
			->setAllianceId($array['alliance_id'])
			->setModifiedTimestamp($array['modified'])
			->setCreatedTimestamp($array['created'])
			->setGameroundId($array['gameround_id'])
			->setTag($array['tag'])
			->setName($array['name'])
			->setDescription($array['description']);
	}
	
	/**
	 * Gibt die Attribute der Allianz als Array zurück
	 * @return array
	 */
	public function toArray()
	{
		return [
			'__className' => __CLASS__,
			'alliance_id' => $this->getAllianceId(),
			'modified' => $this->getModifiedTimestamp(),
			'created' => $this->getCreatedTimestamp(),
			'gameround_id' => $this->getGameroundId(),
			'tag' => $this->getTag(),
			'name' => $this->getName(),
			'description' => $this->getDescription(),
		];
	}
}
