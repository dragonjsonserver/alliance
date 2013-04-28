<?php
/**
 * @link http://dragonjsonserver.de/
 * @copyright Copyright (c) 2012-2013 DragonProjects (http://dragonprojects.de/)
 * @license http://license.dragonprojects.de/dragonjsonserver.txt New BSD License
 * @author Christoph Herrmann <developer@dragonprojects.de>
 * @package DragonJsonServerAlliance
 */

namespace DragonJsonServerAlliance\Service;

/**
 * Serviceklasse zur Verwaltung von Allianzen
 */
class Alliance
{
	use \DragonJsonServer\ServiceManagerTrait;
	use \DragonJsonServer\EventManagerTrait;
	use \DragonJsonServerDoctrine\EntityManagerTrait;
	
    /**
	 * Validiert den übergebenen Tag
	 * @param string $tag
     * @throws \DragonJsonServer\Exception
	 */
	public function validateTag($tag)
	{
		$filter = new \Zend\Filter\StringTrim();
		if ($tag != $filter->filter($tag)) {
			throw new \DragonJsonServer\Exception('invalid tag', ['tag' => $tag]);
		}
		$taglength = $this->getServiceManager()->get('Config')['dragonjsonserveralliance']['taglength'];
		$validator = (new \Zend\Validator\StringLength())
			->setMin($taglength['min'])
			->setMax($taglength['max']);
		if (!$validator->isValid($tag)) {
			throw new \DragonJsonServer\Exception(
				'invalid tag', 
				['tag' => $tag, 'taglength' => $taglength]
			);
		}
	}
	
    /**
	 * Validiert den übergebenen Namen
	 * @param string $name
     * @throws \DragonJsonServer\Exception
	 */
	public function validateName($name)
	{
		$filter = new \Zend\Filter\StringTrim();
		if ($name != $filter->filter($name)) {
			throw new \DragonJsonServer\Exception('invalid name', ['name' => $name]);
		}
		$namelength = $this->getServiceManager()->get('Config')['dragonjsonserveralliance']['namelength'];
		$validator = (new \Zend\Validator\StringLength())
			->setMin($namelength['min'])
			->setMax($namelength['max']);
		if (!$validator->isValid($name)) {
			throw new \DragonJsonServer\Exception(
				'invalid name', 
				['name' => $name, 'namelength' => $namelength]
			);
		}
	}
	
	/**
	 * Erstellt eine Allianz mit dem Avatar als Leader
	 * @param \DragonJsonServerAvatar\Entity\Avatar $avatar
	 * @param string $tag
	 * @param string $name
	 * @return \DragonJsonServerAlliance\Entity\Alliance
	 */
	public function createAlliance(\DragonJsonServerAvatar\Entity\Avatar $avatar, $tag, $name)
	{
		$alliance = (new \DragonJsonServerAlliance\Entity\Alliance())
			->setGameroundId($avatar->getGameroundId())
			->setTag($tag)
			->setName($name);
		$this->getServiceManager()->get('Doctrine')->transactional(function ($entityManager) use ($avatar, $alliance) {
			$entityManager->persist($alliance);
			$entityManager->flush();
			$allianceavatar = $this->getServiceManager()->get('Allianceavatar')->createAllianceavatar($avatar, $alliance, 'leader');
			$this->getEventManager()->trigger(
				(new \DragonJsonServerAlliance\Event\CreateAlliance())
					->setTarget($this)
					->setAlliance($alliance)
					->setAllianceavatar($allianceavatar)
			);
		});
		return $alliance;
	}
	
	/**
	 * Entfernt die übergebene Allianz
	 * @param \DragonJsonServerAlliance\Entity\Alliance $alliance
	 * @return Alliance
	 */
	public function removeAlliance(\DragonJsonServerAlliance\Entity\Alliance $alliance)
	{
		$entityManager = $this->getEntityManager();

		$this->getServiceManager()->get('Doctrine')->transactional(function ($entityManager) use ($alliance) {
			$this->getEventManager()->trigger(
				(new \DragonJsonServerAlliance\Event\RemoveAlliance())
					->setTarget($this)
					->setAlliance($alliance)
			);
			$serviceAllianceavatar = $this->getServiceManager()->get('Allianceavatar');
			$allianceavatars = $serviceAllianceavatar->getAllianceavatarsByAllianceId($alliance->getAllianceId());
			foreach ($allianceavatars as $allianceavatar) {
				$serviceAllianceavatar->removeAllianceavatar($allianceavatar);
			}
			$entityManager->remove($alliance);
			$entityManager->flush();
		});
		return $this;
	}
	
	/**
	 * Gibt die Allianz der übergebenen AllianceID zurück
	 * @param integer $alliance_id
	 * @return \DragonJsonServerAlliance\Entity\Alliance
     * @throws \DragonJsonServer\Exception
	 */
	public function getAllianceByAllianceId($alliance_id)
	{
		$entityManager = $this->getEntityManager();

		$alliance = $entityManager->find('\DragonJsonServerAlliance\Entity\Alliance', $alliance_id);
		if (null === $alliance) {
			throw new \DragonJsonServer\Exception('invalid alliance_id', ['alliance_id' => $alliance_id]);
		}
		return $alliance;
	}
	
	/**
	 * Gibt die Allianz der übergebenen AllianceID und GameroundID zurück
	 * @param integer $alliance_id
	 * @param integer $gameround_id
	 * @return \DragonJsonServerAlliance\Entity\Alliance
     * @throws \DragonJsonServer\Exception
	 */
	public function getAllianceByAllianceIdAndGameroundId($alliance_id, $gameround_id)
	{
		$entityManager = $this->getEntityManager();

		$alliance = $this->getAllianceByAllianceId($alliance_id);
		if ($gameround_id != $alliance->getGameroundId()) {
			throw new \DragonJsonServer\Exception('invalid gameround_id', ['gameround_id' => $alliance->getGameroundId()]);
		}
		return $alliance;
	}
	
	/**
	 * Gibt die Allianz der übergebenen GameroundID, Tag und Namen zurück
	 * @param integer $gameround_id
	 * @param string $tag
	 * @param string $name
	 * @param boolean $throwException
	 * @return \DragonJsonServerAlliance\Entity\Alliance
     * @throws \DragonJsonServer\Exception
	 */
	public function getAllianceByGameroundIdAndTagOrName($gameround_id, $tag, $name, $throwException = true) {
		$entityManager = $this->getEntityManager();
		
		$conditions = ['gameround_id' => $gameround_id, 'tag' => $tag, 'name' => $name];
		$alliances = $entityManager
			->createQuery('
				SELECT alliance FROM \DragonJsonServerAlliance\Entity\Alliance alliance
				WHERE 
					alliance.gameround_id = :gameround_id
					AND
					(
						alliance.tag = :tag
						OR
						alliance.name = :name
					)
			')
			->execute($conditions);
		if (count($alliances) == 0) {
			if ($throwException) {
				throw new \DragonJsonServer\Exception('invalid gameround_id, tag or name', $conditions);
			}
			return;
		}
		return $alliances[0];
	}
	
	/**
	 * Gibt die Allianzen passend zur übergebenen GameroundID und Tag zurück
	 * @param integer $gameround_id
	 * @param string $tag
	 * @return array
     * @throws \DragonJsonServer\Exception
	 */
	public function searchAlliancesByGameroundIdAndTag($gameround_id, $tag)
	{
		$entityManager = $this->getEntityManager();

		$alliances = $entityManager
			->createQuery('
				SELECT alliance FROM \DragonJsonServerAlliance\Entity\Alliance alliance
				WHERE
					alliance.gameround_id = :gameround_id
					AND
					alliance.tag LIKE :tag
			')
			->execute(['gameround_id' => $gameround_id, 'tag' => '%' . $tag . '%']);
		return $alliances;
	}
	
	/**
	 * Gibt die Allianzen passend zur übergebenen GameroundID und Namen zurück
	 * @param integer $gameround_id
	 * @param string $name
	 * @return array
     * @throws \DragonJsonServer\Exception
	 */
	public function searchAlliancesByGameroundIdAndName($gameround_id, $name)
	{
		$entityManager = $this->getEntityManager();

		$alliances = $entityManager
			->createQuery('
				SELECT alliance FROM \DragonJsonServerAlliance\Entity\Alliance alliance
				WHERE
					alliance.gameround_id = :gameround_id
					AND
					alliance.name LIKE :name
			')
			->execute(['gameround_id' => $gameround_id, 'name' => '%' . $name . '%']);
		return $alliances;
	}
	
	/**
	 * Ändert die Beschreibung der übergebenen Allianz
	 * @param \DragonJsonServerAlliance\Entity\Alliance $alliance
	 * @param string $description
	 * @return Alliance
	 */
	public function changeDescription(\DragonJsonServerAlliance\Entity\Alliance $alliance, $description)
	{
		$entityManager = $this->getEntityManager();
		
		$alliance->setDescription($description);
		$entityManager->persist($alliance);
		$entityManager->flush();
		return $this;
	}
}
