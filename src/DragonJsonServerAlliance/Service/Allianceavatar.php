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
	 * Validiert die Allianz auf einen zweiten Leader
	 * @param \DragonJsonServerAlliance\Entity\Allianceavatar $allianceavatar
	 * @return boolean
	 * @throws \DragonJsonServer\Exception
	 */
	public function validateSecondLeader(\DragonJsonServerAlliance\Entity\Allianceavatar $allianceavatar, $throwException = true)
	{
		if ('leader' != $allianceavatar->getRole()) {
			return true;
		}
		$allianceavatars = $this->getAllianceavatarsByAllianceId($allianceavatar->getAllianceId());
		foreach ($allianceavatars as $other_allianceavatar) {
			if ('leader' == $other_allianceavatar->getRole()
				&& $other_allianceavatar->getAvatar()->getAvatarId() != $allianceavatar->getAvatar()->getAvatarId()) {
				return true;
			}
		}
		if ($throwException) {
			throw new \DragonJsonServer\Exception(
				'missing second leader',
				['allianceavatar' => $allianceavatar->toArray()]
			);
		}
		return false;
	}
	
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
			->setAvatar($avatar)
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
	 * @param \DragonJsonServerAvatar\Entity\Avatar $avatar
	 * @param boolean $throwException
	 * @return \DragonJsonServerAlliance\Entity\Allianceavatar|null
     * @throws \DragonJsonServer\Exception
	 */
	public function getAllianceavatarByAvatar($avatar, $throwException = true)
	{
		$entityManager = $this->getEntityManager();

		$allianceavatar = $entityManager
			->getRepository('\DragonJsonServerAlliance\Entity\Allianceavatar')
		    ->findOneBy(['avatar' => $avatar]);
		if (null === $allianceavatar && $throwException) {
			throw new \DragonJsonServer\Exception('invalid avatar', ['avatar' => $avatar->toArray()]);
		}
		return $allianceavatar;
	}
	
	/**
	 * Gibt die Allianzbeziehung mit dem Avatar und AllianceID zurück
	 * @param \DragonJsonServerAvatar\Entity\Avatar $avatar
	 * @param integer $alliance_id
	 * @return \DragonJsonServerAlliance\Entity\Allianceavatar
     * @throws \DragonJsonServer\Exception
	 */
	public function getAllianceavatarByAvatarAndAllianceId(\DragonJsonServerAvatar\Entity\Avatar $avatar, $alliance_id)
	{
		$entityManager = $this->getEntityManager();
		
		$allianceavatar = $this->getAllianceavatarByAvatar($avatar);
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
