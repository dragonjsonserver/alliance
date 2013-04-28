<?php
/**
 * @link http://dragonjsonserver.de/
 * @copyright Copyright (c) 2012-2013 DragonProjects (http://dragonprojects.de/)
 * @license http://license.dragonprojects.de/dragonjsonserver.txt New BSD License
 * @author Christoph Herrmann <developer@dragonprojects.de>
 * @package DragonJsonServerAlliance
 */

namespace DragonJsonServerAlliance\Annotation;

/**
 * Annotation Klasse für die Beziehung des Avatars zu einer Allianz
 * @Annotation
 * @Attributes({
       @Attribute("value", type="array"),
   })
 */
class Alliance extends \Doctrine\Common\Annotations\Annotation
{}
