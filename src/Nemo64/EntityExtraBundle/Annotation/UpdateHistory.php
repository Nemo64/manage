<?php
/**
 * Created by PhpStorm.
 * User: marco
 * Date: 07.03.15
 * Time: 20:31
 */

namespace Nemo64\EntityExtraBundle\Annotation;


use Doctrine\ORM\Mapping\Annotation;

/**
 * @Annotation
 * @Target({"PROPERTY","ANNOTATION"})
 */
class UpdateHistory implements Annotation {

}