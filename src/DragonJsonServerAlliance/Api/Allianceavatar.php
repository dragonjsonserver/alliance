<?php
/**
 * @link http://dragonjsonserver.de/
 * @copyright Copyright (c) 2012-2013 DragonProjects (http://dragonprojects.de/)
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

		$avatar = $serviceManager->get('Avatar')->getAvatar();
		$alliance = $serviceManager->get('Alliance')->getAllianceByAllianceId($alliance_id);
		return $serviceManager->get('Allianceavatar')->createAllianceavatar($avatar, $alliance, 'applicant')->toArray();
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

		$allianceavatar = $serviceManager->get('Allianceavatar')->getAllianceavatar();
		$serviceManager->get('Allianceavatar')->removeAllianceavatar($allianceavatar);
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

		$serviceAllianceavatar = $serviceManager->get('Allianceavatar');
		$allianceavatar = $serviceAllianceavatar->getAllianceavatarByAvatarAndAllianceId(
			$serviceManager->get('Avatar')->getAvatarByAvatarId($other_avatar_id),
			$serviceAllianceavatar->getAllianceavatar()->getAllianceId()
		);
		$serviceAllianceavatar->removeAllianceavatar($allianceavatar);
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
		
		$serviceAllianceavatar = $serviceManager->get('Allianceavatar');
		$allianceavatar = $serviceAllianceavatar->getAllianceavatar();
		$allianceavatars = $serviceAllianceavatar->getAllianceavatarsByAllianceId($allianceavatar->getAllianceId());
		return $serviceManager->get('Doctrine')->toArray($allianceavatars);
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

		$avatar = $serviceManager->get('Avatar')->getAvatar();
		$serviceManager->get('Alliance')->getAllianceByAllianceIdAndGameroundId($alliance_id, $avatar->getGameroundId());
		$allianceavatars = $serviceManager->get('Allianceavatar')->getAllianceavatarsByAllianceId($alliance_id);
		return $serviceManager->get('Doctrine')->toArray($allianceavatars);
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
		$serviceAllianceavatar = $serviceManager->get('Allianceavatar');
		$allianceavatar = $serviceAllianceavatar->getAllianceavatarByAvatarAndAllianceId(
			$serviceManager->get('Avatar')->getAvatarByAvatarId($other_avatar_id),
			$serviceAllianceavatar->getAllianceavatar()->getAllianceId()
		);
		$serviceAllianceavatar->changeRole($allianceavatar, $role);
	}
}
