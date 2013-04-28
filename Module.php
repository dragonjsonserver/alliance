<?php
/**
 * @link http://dragonjsonserver.de/
 * @copyright Copyright (c) 2012-2013 DragonProjects (http://dragonprojects.de/)
 * @license http://license.dragonprojects.de/dragonjsonserver.txt New BSD License
 * @author Christoph Herrmann <developer@dragonprojects.de>
 * @package DragonJsonServerAlliance
 */

namespace DragonJsonServerAlliance;

/**
 * Klasse zur Initialisierung des Moduls
 */
class Module
{
    use \DragonJsonServer\ServiceManagerTrait;
    
    /**
     * Gibt die Konfiguration des Moduls zurück
     * @return array
     */
    public function getConfig()
    {
        return require __DIR__ . '/config/module.config.php';
    }

    /**
     * Gibt die Autoloaderkonfiguration des Moduls zurück
     * @return array
     */
    public function getAutoloaderConfig()
    {
        return [
            'Zend\Loader\StandardAutoloader' => [
                'namespaces' => [
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ],
            ],
        ];
    }
    
    /**
     * Wird bei der Initialisierung des Moduls aufgerufen
     * @param \Zend\ModuleManager\ModuleManager $moduleManager
     */
    public function init(\Zend\ModuleManager\ModuleManager $moduleManager)
    {
    	$sharedManager = $moduleManager->getEventManager()->getSharedManager();
    	$sharedManager->attach('DragonJsonServerApiannotation\Module', 'request', 
	    	function (\DragonJsonServerApiannotation\Event\Request $eventRequest) {
	    		$annotation = $eventRequest->getAnnotation();
	    		if (!$annotation instanceof \DragonJsonServerAlliance\Annotation\Alliance) {
	    			return;
	    		}
	    		$serviceManager = $this->getServiceManager();
	    		$avatar = $serviceManager->get('Avatar')->getAvatar();
	    		if (null === $avatar) {
	    			throw new \DragonJsonServer\Exception('missing avatar');
	    		}
	    		$serviceAllianceavatar = $serviceManager->get('Allianceavatar');
	    		$allianceavatar = $serviceAllianceavatar->getAllianceavatarByAvatarId($avatar->getAvatarId());
	    		$serviceAllianceavatar->setAllianceavatar($allianceavatar);
	    		if (null === $annotation->value) {
	    			return;
	    		}
	    		if (in_array($allianceavatar->getRole(), $annotation->value)) {
		    		return;
	    		}
	    		throw new \DragonJsonServer\Exception(
	    			'invalid role', 
	    			['allianceavatar' => $allianceavatar->toArray(), 'annotation' => $annotation->value]
	    		);
	    	}
    	);
    	$sharedManager->attach('DragonJsonServerApiannotation\Module', 'request', 
	    	function (\DragonJsonServerApiannotation\Event\Request $eventRequest) {
	    		$annotation = $eventRequest->getAnnotation();
	    		if (!$annotation instanceof \DragonJsonServerAlliance\Annotation\Noalliance) {
	    			return;
	    		}
	    		$serviceManager = $this->getServiceManager();
	    		$avatar = $serviceManager->get('Avatar')->getAvatar();
	    		if (null === $avatar) {
	    			throw new \DragonJsonServer\Exception('missing avatar');
	    		}
	    		$allianceavatar = $serviceManager->get('Allianceavatar')->getAllianceavatarByAvatarId($avatar->getAvatarId(), false);
	    		if (null === $allianceavatar) {
	    			return;
	    		}
	    		throw new \DragonJsonServer\Exception(
	    			'avatar already allianceavatar', 
	    			['allianceavatar' => $allianceavatar->toArray()]
	    		);
	    	}
    	);
    }
}
