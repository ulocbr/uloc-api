<?php

namespace Uloc\ApiBundle\Services\ACL;

/**
 * Class AclPolicy
 * Access Control List for all application.
 * @package Uloc\ApiBundle\Services\ACL
 */
class AclPolicy
{
    public static $ACL = [];

    public static function addPolicy($module, $component, $action, $description)
    {
        $namespace = implode('/', [$module, $component, $action]);
        if (isset(self::$ACL[$namespace])) {
            throw new \LogicException(sprintf('Unable to register ACL policy on namespace "%s". Namespace already exists.', $namespace));
        }

        self::$ACL[$namespace] = $description;
    }

    /**
     * @param $acl - Sample: uloc/user/list ... uloc/user/create ... uloc/user/remove
     * @param $aclList
     * @param boolean $exact - If true force to check exact acl namespace. If false, if acl for check is uloc/user/list and $aclList that contains the permissions to be checked contain the level 1 of the namespace, that is, uloc, or level 2, uloc/user, the ACL responds positively, understanding that the user has all lower level permissions.
     * @return boolean
     */
    public static function checkAcl($acl, $aclList = null, $exact = false)
    {
        if (null === $aclList || !is_array($aclList) || empty($aclList)) {
            return false;
        }

        $namespace = explode('/', $acl);

        $levelHistory = '';

        if ($exact) {
            return isset($aclList[$acl]);
        }

        for ($level = 0; $level < count($namespace); $level++) {
            if ($level > 0 && $level < (count($namespace))) {
                $levelHistory .= '/';
            }
            $levelHistory .= $namespace[$level];

            if (isset($aclList[$levelHistory])) {
                return true;
            }
        }

        return false;
    }
}