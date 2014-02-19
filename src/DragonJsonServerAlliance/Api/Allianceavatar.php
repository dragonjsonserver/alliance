<?php
/**
 * @link http://dragonjsonserver.de/
 * @copyright Copyright (c) 2012-2014 DragonProjects (http://dragonprojects.de/)
 * @license http://license.dragonprojects.de/dragonjsonserver.txt New BSD License
 * @author Christoph Herrmann <developer@dragonprojects.de>
 * @package DragonJsonServerAlliance
 */

namespace DragonJsonServerAlliance\Api;

/**
 * API Klasse zur Verwaltung von Allianzbeziehungen
 */
class Allianceavatar
{
	use \DragonJsonServer\ServiceManagerTrait;
	
	/**
	 * Erstellt eine Allianzbewerbung für den aktuellen Avatar
	 * @param integer $alliance_id
	 * @DragonJsonServerAccount\Annotation\Session
	 * @DragonJsonServerAvatar\Annotation\Avatar
	 * @DragonJsonServerAlliance\Annotation\Noalliance
	 */
	public function createAllianceavatar($alliance_id)
	{
		$serviceManager = $this->getServiceManager();

		$avatar = $serviceManager->get('\DragonJsonServerAvatar\Service\Avatar')->getAvatar();
		$alliance = $serviceManager->get('\DragonJsonServerAlliance\Service\Alliance')->getAllianceByAllianceId($alliance_id);
		return $serviceManager->get('\DragonJsonServerAlliance\Service\Allianceavatar')->createAllianceavatar($avatar, $alliance, 'applicant')->toArray();
	}
	
	/**
	 * Entfernt die Allianzbeziehung für den aktuellen Avatar
	 * @DragonJsonServerAccount\Annotation\Session
	 * @DragonJsonServerAvatar\Annotation\Avatar
	 * @DragonJsonServerAlliance\Annotation\Alliance
	 */
	public function removeAllianceavatar()
	{
		$serviceManager = $this->getServiceManager();

		$serviceAllianceavatar = $serviceManager->get('\DragonJsonServerAlliance\Service\Allianceavatar');
		$allianceavatar = $serviceAllianceavatar->getAllianceavatar();
		$serviceAllianceavatar->validateSecondLeader($allianceavatar);
		$serviceAllianceavatar->removeAllianceavatar($allianceavatar);
	}
	
	/**
	 * Entfernt die Allianzbeziehung für den übergebenen Avatar
	 * @param integer $other_avatar_id
	 * @DragonJsonServerAccount\Annotation\Session
	 * @DragonJsonServerAvatar\Annotation\Avatar
	 * @DragonJsonServerAlliance\Annotation\Alliance("leader")
	 */
	public function removeAllianceavatarByAvatarId($other_avatar_id)
	{
		$serviceManager = $this->getServiceManager();

		$serviceAllianceavatar = $serviceManager->get('\DragonJsonServerAlliance\Service\Allianceavatar');
		$allianceavatar = $serviceAllianceavatar->getAllianceavatar();
		if ($allianceavatar->getAvatarId() == $other_avatar_id) {
			throw new \DragonJsonServer\Exception(
					'avatar_id must not match with other_avatar_id',
					['allianceavatar' => $allianceavatar->toArray(), 'other_avatar_id' => $other_avatar_id]
			);
		}
		$other_allianceavatar = $serviceAllianceavatar->getAllianceavatarByAvatarAndAllianceId(
				$serviceManager->get('\DragonJsonServerAvatar\Service\Avatar')->getAvatarByAvatarId($other_avatar_id),
				$allianceavatar->getAllianceId()
		);
		$serviceAllianceavatar->removeAllianceavatar($other_allianceavatar);
	}
	
	/**
	 * Gibt die Allianzbeziehung für die Allianz des aktuellen Avatar zurück
	 * @return array
	 * @DragonJsonServerAccount\Annotation\Session
	 * @DragonJsonServerAvatar\Annotation\Avatar
	 * @DragonJsonServerAlliance\Annotation\Alliance
	 */
	public function getAllianceavatar()
	{
		$serviceManager = $this->getServiceManager();
		
		return $serviceManager->get('\DragonJsonServerAlliance\Service\Allianceavatar')->getAllianceavatar()->toArray();
	}
	
	/**
	 * Gibt die Allianzbeziehungen für die Allianz des aktuellen Avatar zurück
	 * @return array
	 * @DragonJsonServerAccount\Annotation\Session
	 * @DragonJsonServerAvatar\Annotation\Avatar
	 * @DragonJsonServerAlliance\Annotation\Alliance
	 */
	public function getAllianceavatars()
	{
		$serviceManager = $this->getServiceManager();
		
		$serviceAllianceavatar = $serviceManager->get('\DragonJsonServerAlliance\Service\Allianceavatar');
		$allianceavatar = $serviceAllianceavatar->getAllianceavatar();
		$allianceavatars = $serviceAllianceavatar->getAllianceavatarsByAllianceId($allianceavatar->getAllianceId());
		return $serviceManager->get('\DragonJsonServerDoctrine\Service\Doctrine')->toArray($allianceavatars);
	}
	
	/**
	 * Gibt die Allianzbeziehungen für die übergebene AllianceID zurück
	 * @param integer $alliance_id
	 * @return array
	 * @DragonJsonServerAccount\Annotation\Session
	 * @DragonJsonServerAvatar\Annotation\Avatar
	 */
	public function getAllianceavatarsByAllianceId($alliance_id)
	{
		$serviceManager = $this->getServiceManager();

		$avatar = $serviceManager->get('\DragonJsonServerAvatar\Service\Avatar')->getAvatar();
		$serviceManager->get('\DragonJsonServerAlliance\Service\Alliance')->getAllianceByAllianceIdAndGameroundId($alliance_id, $avatar->getGameroundId());
		$allianceavatars = $serviceManager->get('\DragonJsonServerAlliance\Service\Allianceavatar')->getAllianceavatarsByAllianceId($alliance_id);
		return $serviceManager->get('\DragonJsonServerDoctrine\Service\Doctrine')->toArray($allianceavatars);
	}
	
	/**
	 * Gibt die Rollen zurück die für ein Avatar verfügbar sind
	 * @DragonJsonServerAccount\Annotation\Session
	 * @DragonJsonServerAvatar\Annotation\Avatar
	 * @DragonJsonServerAlliance\Annotation\Alliance("leader")
	 */
	public function getRoles()
	{
        return $this->getServiceManager()->get('Config')['dragonjsonserveralliance']['roles'];
	}
	
	/**
	 * Ändert die Rolle der Avatarbeziehung
	 * @param integer $other_avatar_id
	 * @param string $role
	 * @DragonJsonServerAccount\Annotation\Session
	 * @DragonJsonServerAvatar\Annotation\Avatar
	 * @DragonJsonServerAlliance\Annotation\Alliance("leader")
	 */
	public function changeRole($other_avatar_id, $role)
	{
		$serviceManager = $this->getServiceManager();
		
		$roles = $this->getServiceManager()->get('Config')['dragonjsonserveralliance']['roles'];
		if (!in_array($role, $roles)) {
			throw new \DragonJsonServer\Exception('invalid role', ['role' => $role, 'roles' => $roles]);			
		}
		$serviceAllianceavatar = $serviceManager->get('\DragonJsonServerAlliance\Service\Allianceavatar');
		$other_allianceavatar = $serviceAllianceavatar->getAllianceavatarByAvatarAndAllianceId(
				$serviceManager->get('\DragonJsonServerAvatar\Service\Avatar')->getAvatarByAvatarId($other_avatar_id),
				$serviceAllianceavatar->getAllianceavatar()->getAllianceId()
		);
		$serviceAllianceavatar->validateSecondLeader($other_allianceavatar);
		$serviceAllianceavatar->changeRole($other_allianceavatar, $role);
	}
}
