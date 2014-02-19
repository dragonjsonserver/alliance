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
 * API Klasse zur Verwaltung von Allianzen
 */
class Alliance
{
	use \DragonJsonServer\ServiceManagerTrait;

	/**
	 * Validiert den übergebenen Tag und Namen
	 * @param string $tag
	 * @param string $name
     * @DragonJsonServerAccount\Annotation\Session
	 * @DragonJsonServerAvatar\Annotation\Avatar
	 * @DragonJsonServerAlliance\Annotation\Noalliance
	 */
	public function validateTagAndName($tag, $name)
	{
		$serviceManager = $this->getServiceManager();

		$serviceAlliance = $serviceManager->get('\DragonJsonServerAlliance\Service\Alliance');
		$serviceAlliance
			->validateTag($tag)
			->validateName($name);
		$avatar = $serviceManager->get('\DragonJsonServerAvatar\Service\Avatar')->getAvatar();
		$alliance = $serviceAlliance->getAllianceByGameroundIdAndTagOrName($avatar->getGameroundId(), $tag, $name, false);
		if (null !== $alliance) {
			throw new \DragonJsonServer\Exception(
				'tag or name not unique', 
				['tag' => $tag, 'name' => $name, 'alliance' => $alliance->toArray()]
			);
		}
	}
	
	/**
	 * Erstellt eine Allianz mit dem aktuellen Avatar als Leader
	 * @param string $tag
	 * @param string $name
	 * @return array
	 * @DragonJsonServerAccount\Annotation\Session
	 * @DragonJsonServerAvatar\Annotation\Avatar
	 * @DragonJsonServerAlliance\Annotation\Noalliance
	 */
	public function createAlliance($tag, $name)
	{
		$this->validateTagAndName($tag, $name);
		$serviceManager = $this->getServiceManager();

		$avatar = $serviceManager->get('\DragonJsonServerAvatar\Service\Avatar')->getAvatar();
		return $serviceManager->get('\DragonJsonServerAlliance\Service\Alliance')->createAlliance($avatar, $tag, $name)->toArray();
	}
	
	/**
	 * Entfernt die Allianz des aktuellen Avatars
	 * @DragonJsonServerAccount\Annotation\Session
	 * @DragonJsonServerAvatar\Annotation\Avatar
	 * @DragonJsonServerAlliance\Annotation\Alliance("leader")
	 */
	public function removeAlliance()
	{
		$serviceManager = $this->getServiceManager();

		$allianceavatar = $serviceManager->get('\DragonJsonServerAlliance\Service\Allianceavatar')->getAllianceavatar();
		$serviceAlliance = $serviceManager->get('\DragonJsonServerAlliance\Service\Alliance');
		$alliance = $serviceAlliance->getAllianceByAllianceId($allianceavatar->getAllianceId());
		$serviceAlliance->removeAlliance($alliance);
	}
	
	/**
	 * Gibt die Allianz des aktuellen Avatars zurück
	 * @return array
	 * @DragonJsonServerAccount\Annotation\Session
	 * @DragonJsonServerAvatar\Annotation\Avatar
	 * @DragonJsonServerAlliance\Annotation\Alliance
	 */
	public function getAlliance()
	{
		$serviceManager = $this->getServiceManager();

		$allianceavatar = $serviceManager->get('\DragonJsonServerAlliance\Service\Allianceavatar')->getAllianceavatar();
		return $serviceManager->get('\DragonJsonServerAlliance\Service\Alliance')->getAllianceByAllianceId($allianceavatar->getAllianceId())->toArray();
	}
	
	/**
	 * Gibt die Allianz der übergebenen AllianceID zurück
	 * @param integer $alliance_id
	 * @return array
	 * @DragonJsonServerAccount\Annotation\Session
	 * @DragonJsonServerAvatar\Annotation\Avatar
	 */
	public function getAllianceById($alliance_id)
	{
		$serviceManager = $this->getServiceManager();

		$avatar = $serviceManager->get('\DragonJsonServerAvatar\Service\Avatar')->getAvatar();
		return $serviceManager->get('\DragonJsonServerAlliance\Service\Alliance')
			->getAllianceByAllianceIdAndGameroundId($alliance_id, $avatar->getGameroundId())->toArray();
	}
	
	/**
	 * Gibt die Allianzen passend zum übergebenen Tag zurück
	 * @param string $tag
	 * @return array
	 * @DragonJsonServerAccount\Annotation\Session
	 * @DragonJsonServerAvatar\Annotation\Avatar
	 */
	public function searchAlliancesByTag($tag)
	{
		$serviceManager = $this->getServiceManager();

		$avatar = $serviceManager->get('\DragonJsonServerAvatar\Service\Avatar')->getAvatar();
		$alliances = $serviceManager->get('\DragonJsonServerAlliance\Service\Alliance')
			->searchAlliancesByGameroundIdAndTag($avatar->getGameroundId(), $tag);
		return $serviceManager->get('\DragonJsonServerDoctrine\Service\Doctrine')->toArray($alliances);
	}
	
	/**
	 * Gibt die Allianzen passend zum übergebenen Namen zurück
	 * @param string $name
	 * @return array
	 * @DragonJsonServerAccount\Annotation\Session
	 * @DragonJsonServerAvatar\Annotation\Avatar
	 */
	public function searchAlliancesByName($name)
	{
		$serviceManager = $this->getServiceManager();

		$avatar = $serviceManager->get('\DragonJsonServerAvatar\Service\Avatar')->getAvatar();
		$alliances = $serviceManager->get('\DragonJsonServerAlliance\Service\Alliance')
			->searchAlliancesByGameroundIdAndName($avatar->getGameroundId(), $name);
		return $serviceManager->get('\DragonJsonServerDoctrine\Service\Doctrine')->toArray($alliances);
	}
	
	/**
	 * Ändert die Beschreibung der Allianz des aktuellen Avatars
	 * @param string $description
	 * @DragonJsonServerAccount\Annotation\Session
	 * @DragonJsonServerAvatar\Annotation\Avatar
	 * @DragonJsonServerAlliance\Annotation\Alliance("leader")
	 */
	public function changeDescription($description)
	{
		$serviceManager = $this->getServiceManager();
		
		$allianceavatar = $serviceManager->get('\DragonJsonServerAlliance\Service\Allianceavatar')->getAllianceavatar();
		$serviceAlliance = $serviceManager->get('\DragonJsonServerAlliance\Service\Alliance');
		$alliance = $serviceAlliance->getAllianceByAllianceId($allianceavatar->getAllianceId());
		$serviceAlliance->changeDescription($alliance, $description);
	}
}
