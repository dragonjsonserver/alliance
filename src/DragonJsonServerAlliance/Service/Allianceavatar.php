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
 * Serviceklasse zur Verwaltung von Allianzbeziehungen
 */
class Allianceavatar
{
	use \DragonJsonServer\ServiceManagerTrait;
	use \DragonJsonServer\EventManagerTrait;
	use \DragonJsonServerDoctrine\EntityManagerTrait;
	
	/**
	 * @var \DragonJsonServerAlliance\Entity\Allianceavatar
	 */
	protected $allianceavatar;
	
	/**
	 * Erstellt eine Allianzbeziehung mit der übergebenen Rolle
	 * @param \DragonJsonServerAvatar\Entity\Avatar $avatar
	 * @param \DragonJsonServerAlliance\Entity\Alliance $alliance
	 * @param string $role
	 * @return \DragonJsonServerAlliance\Entity\Allianceavatar
	 */
	public function createAllianceavatar(\DragonJsonServerAvatar\Entity\Avatar $avatar, 
										 \DragonJsonServerAlliance\Entity\Alliance $alliance,
										 $role)
	{
		$allianceavatar = (new \DragonJsonServerAlliance\Entity\Allianceavatar())
			->setAvatarId($avatar->getAvatarId())
			->setAllianceId($alliance->getAllianceId())
			->setRole($role);
		$this->getServiceManager()->get('Doctrine')->transactional(function ($entityManager) use ($alliance, $allianceavatar) {
			$entityManager->persist($allianceavatar);
			$entityManager->flush();
			$this->getEventManager()->trigger(
				(new \DragonJsonServerAlliance\Event\CreateAllianceavatar())
					->setTarget($this)
					->setAlliance($alliance)
					->setAllianceavatar($allianceavatar)
			);
		});
		return $allianceavatar;
	}
	
	/**
	 * Entfernt die übergebene Allianzbeziehung
	 * @param \DragonJsonServerAlliance\Entity\Allianceavatar $allianceavatar
	 * @return Allianceavatar
	 */
	public function removeAllianceavatar(\DragonJsonServerAlliance\Entity\Allianceavatar $allianceavatar)
	{
		$entityManager = $this->getEntityManager();

		$this->getServiceManager()->get('Doctrine')->transactional(function ($entityManager) use ($allianceavatar) {
			$this->getEventManager()->trigger(
				(new \DragonJsonServerAlliance\Event\RemoveAllianceavatar())
					->setTarget($this)
					->setAllianceavatar($allianceavatar)
			);
			$entityManager->remove($allianceavatar);
			$entityManager->flush();
		});
		return $this;
	}
	
	/**
	 * Setzt die Allianzbeziehung des aktuellen Avatars 
	 * @param \DragonJsonServerAlliance\Entity\Allianceavatar $allianceavatar
	 * @return Allianceavatar
	 */
	public function setAllianceavatar(\DragonJsonServerAlliance\Entity\Allianceavatar $allianceavatar)
	{
		$this->allianceavatar = $allianceavatar;
		return $this;
	}
	
	/**
	 * Gibt die Allianzbeziehung des aktuellen Avatars zurück 
	 * @return \DragonJsonServerAlliance\Entity\Allianceavatar|null
	 */
	public function getAllianceavatar()
	{
		return $this->allianceavatar;
	}
	
	/**
	 * Gibt die Allianzbeziehung mit der übergebenen AvatarID zurück
	 * @param integer $avatar_id
	 * @param boolean $throwException
	 * @return \DragonJsonServerAlliance\Entity\Allianceavatar|null
     * @throws \DragonJsonServer\Exception
	 */
	public function getAllianceavatarByAvatarId($avatar_id, $throwException = true)
	{
		$entityManager = $this->getEntityManager();

		$conditions = ['avatar_id' => $avatar_id];
		$allianceavatar = $entityManager
			->getRepository('\DragonJsonServerAlliance\Entity\Allianceavatar')
		    ->findOneBy($conditions);
		if (null === $allianceavatar && $throwException) {
			throw new \DragonJsonServer\Exception('invalid avatar_id', $conditions);
		}
		return $allianceavatar;
	}
	
	/**
	 * Gibt die Allianzbeziehung mit der AvatarID und AllianceID zurück
	 * @param integer $avatar_id
	 * @param integer $alliance_id
	 * @return \DragonJsonServerAlliance\Entity\Allianceavatar
     * @throws \DragonJsonServer\Exception
	 */
	public function getAllianceavatarByAvatarIdAndAllianceId($avatar_id, $alliance_id)
	{
		$entityManager = $this->getEntityManager();
		
		$allianceavatar = $this->getAllianceavatarByAvatarId($avatar_id);
		if ($alliance_id != $allianceavatar->getAllianceId()) {
			throw new \DragonJsonServer\Exception(
				'alliance_id not match',
				['alliance_id' => $alliance_id, 'allianceavatar' => $allianceavatar->toArray()]
			);
		}
		return $allianceavatar;
	}
	
	/**
	 * Gibt die Allianzbeziehungen mit der übergebenen AllianceID zurück
	 * @param integer $alliance_id
	 * @return array
	 */
	public function getAllianceavatarsByAllianceId($alliance_id)
	{
		$entityManager = $this->getEntityManager();
		
		return $entityManager
			->getRepository('\DragonJsonServerAlliance\Entity\Allianceavatar')
			->findBy(['alliance_id' => $alliance_id]);
	}
	
	/**
	 * Ändert die Rolle der Avatarbeziehung
	 * @param \DragonJsonServerAlliance\Entity\Allianceavatar $allianceavatar
	 * @param string $role
	 * @return Allianceavatar
	 */
	public function changeRole(\DragonJsonServerAlliance\Entity\Allianceavatar $allianceavatar, $role)
	{
		$entityManager = $this->getEntityManager();

		$this->getEventManager()->trigger(
			(new \DragonJsonServerAlliance\Event\ChangeRole())
				->setTarget($this)
				->setAllianceavatar($allianceavatar)
				->setRole($role)
		);
		$allianceavatar->setRole($role);
		$entityManager->persist($allianceavatar);
		$entityManager->flush();
		return $this;
	}
}
