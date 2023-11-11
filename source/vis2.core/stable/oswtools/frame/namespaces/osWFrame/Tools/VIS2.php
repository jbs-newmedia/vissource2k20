<?php declare(strict_types=0);

/**
 * This file is part of the osWFrame package
 *
 * @author Juergen Schwind
 * @copyright Copyright (c) JBS New Media GmbH - Juergen Schwind (https://jbs-newmedia.com)
 * @package osWFrame
 * @link https://oswframe.com
 * @license MIT License
 */

namespace osWFrame\Tools;

use osWFrame\Core as Frame;

class VIS2
{
    use Frame\BaseStaticTrait;

    /**
     * Major-Version der Klasse.
     */
    private const CLASS_MAJOR_VERSION = 1;

    /**
     * Minor-Version der Klasse.
     */
    private const CLASS_MINOR_VERSION = 0;

    /**
     * Release-Version der Klasse.
     */
    private const CLASS_RELEASE_VERSION = 0;

    /**
     * Extra-Version der Klasse.
     * Zum Beispiel alpha, beta, rc1, rc2 ...
     */
    private const CLASS_EXTRA_VERSION = '';

    /**
     */
    private bool $connected = false;

    /**
     */
    private array $users = [];

    /**
     */
    private array $jsons = [];

    /**
     */
    private int $user_id = 0;

    /**
     */
    private ?object $object = null;

    /**
     *
     */
    public function __construct(object $object)
    {
        $this->object = $object;
        if (($this->object->getJSONStringValue('database_server') !== '') && ($this->object->getJSONStringValue('database_username') !== '') && ($this->object->getJSONStringValue('database_db') !== '')) {
            \osWFrame\Core\DB::addConnectionMYSQL($this->object->getJSONStringValue('database_server'), $this->object->getJSONStringValue('database_username'), $this->object->getJSONStringValue('database_password'), $this->object->getJSONStringValue('database_db'));
            if (\osWFrame\Core\DB::connect() === true) {
                $this->connected = true;
            }
        }
    }

    /**
     */
    public function isConnected(): bool
    {
        return $this->connected;
    }

    /**
     */
    public function loadUsers(): bool
    {
        if ($this->isConnected() !== true) {
            return false;
        }
        $QreadData = new \osWFrame\Core\Database();
        $QreadData->prepare('SELECT user_id, user_email, user_lastname, user_firstname FROM :table_vis2_user: WHERE user_status=:user_status: ORDER BY user_lastname ASC, user_email DESC');
        $QreadData->bindRaw(':table_vis2_user:', $this->object->getJSONStringValue('database_prefix') . 'vis2_user');
        $QreadData->bindInt(':user_status:', 1);
        foreach ($QreadData->query() as $user) {
            $this->users[$user['user_id']] = $user['user_lastname'] . ' ' . $user['user_firstname'] . ' (' . $user['user_email'] . ')';
            if ($this->object->getJSONIntValue('vis2_admin_user_id') === $user['user_id']) {
                $this->user_id = $user['user_id'];
            }
        }

        return true;
    }

    /**
     */
    public function getUsers(): array
    {
        return $this->users;
    }

    /**
     */
    public function geUserId(): int
    {
        return $this->user_id;
    }

    /**
     */
    public function getJSONs(): array
    {
        if ($this->jsons === []) {
            $files = glob(Frame\Settings::getStringVar('settings_abspath') . 'resources' . \DIRECTORY_SEPARATOR . 'json' . \DIRECTORY_SEPARATOR . 'vis2' . \DIRECTORY_SEPARATOR . '*.json');
            foreach ($files as $file) {
                $data = json_decode(file_get_contents($file), true);
                if ((isset($data['tool'])) && (isset($data['tool']['tool_name'])) && (isset($data['tool']['tool_name_intern']))) {
                    $this->jsons[$data['tool']['tool_name_intern']] = ['name_intern' => $data['tool']['tool_name_intern'], 'name' => $data['tool']['tool_name'], 'file' => str_replace(Frame\Settings::getStringVar('settings_abspath') . 'resources' . \DIRECTORY_SEPARATOR . 'json' . \DIRECTORY_SEPARATOR . 'vis2' . \DIRECTORY_SEPARATOR, '', $file)];
                }
            }
        }

        return $this->jsons;
    }

    /**
     */
    public function runJSON(string $file, int $time = 0): bool
    {
        if ($this->isConnected() !== true) {
            return false;
        }
        if ($time === 0) {
            $time = time();
        }

        /*
         * User
         */
        if ($this->object->getJSONIntValue('vis2_admin_user_id') > 0) {
            $QreadData = new \osWFrame\Core\Database();
            $QreadData->prepare('SELECT user_id FROM :table_vis2_user: WHERE user_id=:user_id:');
            $QreadData->bindRaw(':table_vis2_user:', $this->object->getJSONStringValue('database_prefix') . 'vis2_user');
            $QreadData->bindInt(':user_id:', $this->object->getJSONIntValue('vis2_admin_user_id'));
            $QreadData->execute();
            if ($QreadData->rowCount() === 0) {
                $QinsertData = new \osWFrame\Core\Database();
                $QinsertData->prepare('INSERT INTO :table_vis2_user: (user_email, user_name, user_form, user_firstname, user_lastname, user_gender, user_password, user_status, user_create_time, user_update_time) VALUES (:user_email:, :user_name:, :user_form:, :user_firstname:, :user_lastname:, :user_gender:, :user_password:, :user_status:, :user_create_time:, :user_update_time:)');
                $QinsertData->bindRaw(':table_vis2_user:', $this->object->getJSONStringValue('database_prefix') . 'vis2_user');
                $QinsertData->bindString(':user_email:', $this->object->getJSONStringValue('vis2_admin_email'));
                $QinsertData->bindString(':user_name:', $this->object->getJSONStringValue('vis2_admin_username'));
                $QinsertData->bindString(':user_form:', $this->object->getJSONStringValue('vis2_admin_form'));
                $QinsertData->bindString(':user_firstname:', $this->object->getJSONStringValue('vis2_admin_firstname'));
                $QinsertData->bindString(':user_lastname:', $this->object->getJSONStringValue('vis2_admin_lastname'));
                $QinsertData->bindInt(':user_gender:', $this->object->getJSONIntValue('vis2_admin_gender'));
                $QinsertData->bindString(':user_password:', $this->object->getJSONStringValue('vis2_admin_password'));
                $QinsertData->bindInt(':user_status:', 1);
                $QinsertData->bindInt(':user_create_time:', $time);
                $QinsertData->bindInt(':user_update_time:', $time);
                $QinsertData->execute();
                $this->user_id = $QinsertData->lastInsertId();

                $QupdateData = new \osWFrame\Core\Database();
                $QupdateData->prepare('UPDATE :table_vis2_user: SET user_create_user_id=:user_create_user_id:, user_update_user_id=:user_update_user_id: WHERE user_id=:user_id:');
                $QupdateData->bindRaw(':table_vis2_user:', $this->object->getJSONStringValue('database_prefix') . 'vis2_user');
                $QupdateData->bindInt(':user_create_user_id:', $this->user_id);
                $QupdateData->bindInt(':user_update_user_id:', $this->user_id);
                $QupdateData->bindInt(':user_id:', $this->user_id);
                $QupdateData->execute();
            } else {
                $this->user_id = $this->object->getJSONIntValue('vis2_admin_user_id');
            }
        } else {
            $QreadData = new \osWFrame\Core\Database();
            $QreadData->prepare('SELECT user_id FROM :table_vis2_user: WHERE user_email=:user_email:');
            $QreadData->bindRaw(':table_vis2_user:', $this->object->getJSONStringValue('database_prefix') . 'vis2_user');
            $QreadData->bindString(':user_email:', $this->object->getJSONStringValue('vis2_admin_email'));
            $QreadData->execute();
            if ($QreadData->rowCount() === 0) {
                $QinsertData = new \osWFrame\Core\Database();
                $QinsertData->prepare('INSERT INTO :table_vis2_user: (user_email, user_name, user_form, user_firstname, user_lastname, user_gender, user_password, user_status, user_create_time, user_update_time) VALUES (:user_email:, :user_name:, :user_form:, :user_firstname:, :user_lastname:, :user_gender:, :user_password:, :user_status:, :user_create_time:, :user_update_time:)');
                $QinsertData->bindRaw(':table_vis2_user:', $this->object->getJSONStringValue('database_prefix') . 'vis2_user');
                $QinsertData->bindString(':user_email:', $this->object->getJSONStringValue('vis2_admin_email'));
                $QinsertData->bindString(':user_name:', $this->object->getJSONStringValue('vis2_admin_username'));
                $QinsertData->bindString(':user_form:', $this->object->getJSONStringValue('vis2_admin_form'));
                $QinsertData->bindString(':user_firstname:', $this->object->getJSONStringValue('vis2_admin_firstname'));
                $QinsertData->bindString(':user_lastname:', $this->object->getJSONStringValue('vis2_admin_lastname'));
                $QinsertData->bindInt(':user_gender:', $this->object->getJSONStringValue('vis2_admin_gender'));
                $QinsertData->bindString(':user_password:', $this->object->getJSONStringValue('vis2_admin_password'));
                $QinsertData->bindInt(':user_status:', 1);
                $QinsertData->bindInt(':user_create_time:', $time);
                $QinsertData->bindInt(':user_update_time:', $time);
                $QinsertData->execute();
                $this->user_id = $QinsertData->lastInsertId();

                $QupdateData = new \osWFrame\Core\Database();
                $QupdateData->prepare('UPDATE :table_vis2_user: SET user_create_user_id=:user_create_user_id:, user_update_user_id=:user_update_user_id: WHERE user_id=:user_id:');
                $QupdateData->bindRaw(':table_vis2_user:', $this->object->getJSONStringValue('database_prefix') . 'vis2_user');
                $QupdateData->bindInt(':user_create_user_id:', $this->user_id);
                $QupdateData->bindInt(':user_update_user_id:', $this->user_id);
                $QupdateData->bindInt(':user_id:', $this->user_id);
                $QupdateData->execute();
            } else {
                $_result = $QreadData->fetch();
                $this->user_id = $_result['user_id'];
                $QupdateData = new \osWFrame\Core\Database();
                $QupdateData->prepare('UPDATE :table_vis2_user: SET user_email=:user_email:, user_name=:user_name:, user_form=:user_form:, user_firstname=:user_firstname:, user_lastname=:user_lastname:, user_gender=:user_gender:, user_password=:user_password:, user_status=:user_status: WHERE user_id=:user_id:');
                $QupdateData->bindRaw(':table_vis2_user:', $this->object->getJSONStringValue('database_prefix') . 'vis2_user');
                $QupdateData->bindString(':user_email:', $this->object->getJSONStringValue('vis2_admin_email'));
                $QupdateData->bindString(':user_name:', $this->object->getJSONStringValue('vis2_admin_username'));
                $QupdateData->bindString(':user_form:', $this->object->getJSONStringValue('vis2_admin_form'));
                $QupdateData->bindString(':user_firstname:', $this->object->getJSONStringValue('vis2_admin_firstname'));
                $QupdateData->bindString(':user_lastname:', $this->object->getJSONStringValue('vis2_admin_lastname'));
                $QupdateData->bindInt(':user_gender:', $this->object->getJSONStringValue('vis2_admin_gender'));
                $QupdateData->bindString(':user_password:', $this->object->getJSONStringValue('vis2_admin_password'));
                $QupdateData->bindInt(':user_status:', 1);
                $QupdateData->bindInt(':user_id:', $this->user_id);
                $QupdateData->execute();
                if ($QupdateData->rowCount() > 0) {
                    $QupdateData = new \osWFrame\Core\Database();
                    $QupdateData->prepare('UPDATE :table_vis2_user: SET user_update_time=:user_update_time:, user_update_user_id=:user_update_user_id: WHERE user_id=:user_id:');
                    $QupdateData->bindRaw(':table_vis2_user:', $this->object->getJSONStringValue('database_prefix') . 'vis2_user');
                    $QupdateData->bindInt(':user_update_time:', $time);
                    $QupdateData->bindInt(':user_update_user_id:', $this->user_id);
                    $QupdateData->bindInt(':user_id:', $this->user_id);
                    $QupdateData->execute();
                }
            }
        }
        if ($file !== '') {
            $file = Frame\Settings::getStringVar('settings_abspath') . 'resources' . \DIRECTORY_SEPARATOR . 'json' . \DIRECTORY_SEPARATOR . 'vis2' . \DIRECTORY_SEPARATOR . $file;
            if (Frame\Filesystem::existsFile($file) !== true) {
                return false;
            }
            $_vis2_script = json_decode(file_get_contents($file), true);
            $files = glob(Frame\Settings::getStringVar('settings_abspath') . 'resources' . \DIRECTORY_SEPARATOR . 'json' . \DIRECTORY_SEPARATOR . 'vis2' . \DIRECTORY_SEPARATOR . 'vis2_' . $_vis2_script['tool']['tool_name_intern'] . \DIRECTORY_SEPARATOR . '*.json');
            foreach ($files as $file) {
                $data = json_decode(file_get_contents($file), true);
                if (isset($data['group'])) {
                    foreach ($data['group'] as $key => $value) {
                        $_vis2_script['group'][$key] = $value;
                    }
                }
                if (isset($data['permission'])) {
                    foreach ($data['permission'] as $key => $value) {
                        $_vis2_script['permission'][$key] = $value;
                    }
                }
                if (isset($data['navigation'])) {
                    foreach ($data['navigation'] as $key => $value) {
                        $_vis2_script['navigation'][$key] = $value;
                    }
                }
            }

            /**
             * Tool
             */
            $QreadData = new \osWFrame\Core\Database();
            $QreadData->prepare('SELECT tool_id FROM :table_vis2_tool: WHERE tool_name_intern=:tool_name_intern:');
            $QreadData->bindRaw(':table_vis2_tool:', $this->object->getJSONStringValue('database_prefix') . 'vis2_tool');
            $QreadData->bindString(':tool_name_intern:', $_vis2_script['tool']['tool_name_intern']);
            $QreadData->execute();
            if ($QreadData->rowCount() === 0) {
                $QinsertData = new \osWFrame\Core\Database();
                $QinsertData->prepare('INSERT INTO :table_vis2_tool: (tool_name, tool_name_intern, tool_description, tool_ispublic, tool_hide_logon, tool_hide_navigation, tool_use_mandant, tool_use_mandantswitch, tool_create_time, tool_create_user_id, tool_update_time, tool_update_user_id) VALUES (:tool_name:, :tool_name_intern:, :tool_description:, :tool_ispublic:, :tool_hide_logon:, :tool_hide_navigation:, :tool_use_mandant:, :tool_use_mandantswitch:, :tool_create_time:, :tool_create_user_id:, :tool_update_time:, :tool_update_user_id:)');
                $QinsertData->bindRaw(':table_vis2_tool:', $this->object->getJSONStringValue('database_prefix') . 'vis2_tool');
                $QinsertData->bindString(':tool_name:', $_vis2_script['tool']['tool_name']);
                $QinsertData->bindString(':tool_name_intern:', $_vis2_script['tool']['tool_name_intern']);
                $QinsertData->bindString(':tool_description:', $_vis2_script['tool']['tool_description']);
                $QinsertData->bindInt(':tool_ispublic:', $_vis2_script['tool']['tool_ispublic']);
                $QinsertData->bindInt(':tool_hide_logon:', $_vis2_script['tool']['tool_hide_logon']);
                $QinsertData->bindInt(':tool_hide_navigation:', $_vis2_script['tool']['tool_hide_navigation']);
                $QinsertData->bindInt(':tool_use_mandant:', $_vis2_script['tool']['tool_use_mandant']);
                $QinsertData->bindInt(':tool_use_mandantswitch:', $_vis2_script['tool']['tool_use_mandantswitch']);
                $QinsertData->bindInt(':tool_create_time:', $time);
                $QinsertData->bindInt(':tool_create_user_id:', $this->user_id);
                $QinsertData->bindInt(':tool_update_time:', $time);
                $QinsertData->bindInt(':tool_update_user_id:', $this->user_id);
                $QinsertData->execute();
                $tool_id = $QinsertData->lastInsertId();
            } else {
                $_result = $QreadData->fetch();
                $QupdateData = new \osWFrame\Core\Database();
                $QupdateData->prepare('UPDATE :table_vis2_tool: SET tool_name=:tool_name:, tool_description=:tool_description:, tool_ispublic=:tool_ispublic:, tool_hide_logon=:tool_hide_logon:, tool_hide_navigation=:tool_hide_navigation:, tool_use_mandant=:tool_use_mandant:, tool_use_mandantswitch=:tool_use_mandantswitch: WHERE tool_id=:tool_id:');
                $QupdateData->bindRaw(':table_vis2_tool:', $this->object->getJSONStringValue('database_prefix') . 'vis2_tool');
                $QupdateData->bindString(':tool_name:', $_vis2_script['tool']['tool_name']);
                $QupdateData->bindString(':tool_description:', $_vis2_script['tool']['tool_description']);
                $QupdateData->bindString(':tool_ispublic:', $_vis2_script['tool']['tool_ispublic']);
                $QupdateData->bindString(':tool_hide_logon:', $_vis2_script['tool']['tool_hide_logon']);
                $QupdateData->bindString(':tool_hide_navigation:', $_vis2_script['tool']['tool_hide_navigation']);
                $QupdateData->bindInt(':tool_use_mandant:', $_vis2_script['tool']['tool_use_mandant']);
                $QupdateData->bindInt(':tool_use_mandantswitch:', $_vis2_script['tool']['tool_use_mandantswitch']);
                $QupdateData->bindInt(':tool_id:', $_result['tool_id']);
                $QupdateData->execute();
                if ($QupdateData->rowCount() > 0) {
                    $QupdateData = new \osWFrame\Core\Database();
                    $QupdateData->prepare('UPDATE :table_vis2_tool: SET tool_update_time=:tool_update_time:, tool_update_user_id=:tool_update_user_id: WHERE tool_id=:tool_id:');
                    $QupdateData->bindRaw(':table_vis2_tool:', $this->object->getJSONStringValue('database_prefix') . 'vis2_tool');
                    $QupdateData->bindInt(':tool_update_time:', $time);
                    $QupdateData->bindInt(':tool_update_user_id:', $this->user_id);
                    $QupdateData->bindInt(':tool_id:', $_result['tool_id']);
                    $QupdateData->execute();
                }
                $tool_id = $_result['tool_id'];
            }

            /**
             * Group
             */
            $ar_groups = [];
            foreach ($_vis2_script['group'] as $id => $group) {
                $QreadData = new \osWFrame\Core\Database();
                $QreadData->prepare('SELECT group_id FROM :table_vis2_group: WHERE tool_id=:tool_id: AND group_name_intern=:group_name_intern:');
                $QreadData->bindRaw(':table_vis2_group:', $this->object->getJSONStringValue('database_prefix') . 'vis2_group');
                $QreadData->bindInt(':tool_id:', $tool_id);
                $QreadData->bindString(':group_name_intern:', $group['group_name_intern']);
                $QreadData->execute();
                if ($QreadData->rowCount() === 0) {
                    $QinsertData = new \osWFrame\Core\Database();
                    $QinsertData->prepare('INSERT INTO :table_vis2_group: (group_name_intern, group_name, group_description, group_ispublic, tool_id, group_create_time, group_create_user_id, group_update_time, group_update_user_id) VALUES (:group_name_intern:, :group_name:, :group_description:, :group_ispublic:, :tool_id:, :group_create_time:, :group_create_user_id:, :group_update_time:, :group_update_user_id:)');
                    $QinsertData->bindRaw(':table_vis2_group:', $this->object->getJSONStringValue('database_prefix') . 'vis2_group');
                    $QinsertData->bindString(':group_name:', $group['group_name']);
                    $QinsertData->bindString(':group_name_intern:', $group['group_name_intern']);
                    $QinsertData->bindString(':group_description:', $group['group_description']);
                    $QinsertData->bindInt(':group_ispublic:', $group['group_ispublic']);
                    $QinsertData->bindInt(':group_create_time:', $time);
                    $QinsertData->bindInt(':group_create_user_id:', $this->user_id);
                    $QinsertData->bindInt(':group_update_time:', $time);
                    $QinsertData->bindInt(':group_update_user_id:', $this->user_id);
                    $QinsertData->bindInt(':tool_id:', $tool_id);
                    $QinsertData->execute();
                    $group_id = $QinsertData->lastInsertId();
                } else {
                    $_result = $QreadData->fetch();
                    $QupdateData = new \osWFrame\Core\Database();
                    $QupdateData->prepare('UPDATE :table_vis2_group: SET group_name=:group_name:, group_description=:group_description:, group_ispublic=:group_ispublic:, tool_id=:tool_id: WHERE group_id=:group_id:');
                    $QupdateData->bindRaw(':table_vis2_group:', $this->object->getJSONStringValue('database_prefix') . 'vis2_group');
                    $QupdateData->bindString(':group_name:', $group['group_name']);
                    $QupdateData->bindString(':group_description:', $group['group_description']);
                    $QupdateData->bindInt(':group_ispublic:', $group['group_ispublic']);
                    $QupdateData->bindInt(':group_id:', $_result['group_id']);
                    $QupdateData->bindInt(':tool_id:', $tool_id);
                    $QupdateData->execute();
                    if ($QupdateData->rowCount() > 0) {
                        $QupdateData = new \osWFrame\Core\Database();
                        $QupdateData->prepare('UPDATE :table_vis2_group: SET group_update_time=:group_update_time:, group_update_user_id=:group_update_user_id: WHERE group_id=:group_id:');
                        $QupdateData->bindRaw(':table_vis2_group:', $this->object->getJSONStringValue('database_prefix') . 'vis2_group');
                        $QupdateData->bindInt(':group_update_time:', $time);
                        $QupdateData->bindInt(':group_update_user_id:', $this->user_id);
                        $QupdateData->bindInt(':group_id:', $_result['group_id']);
                        $QupdateData->execute();
                    }
                    $group_id = $_result['group_id'];
                }
                $ar_groups[$id] = $group_id;
            }

            /*
             * Permission
             */
            foreach ($_vis2_script['permission'] as $permission) {
                $QreadData = new \osWFrame\Core\Database();
                $QreadData->prepare('SELECT permission_id FROM :table_vis2_permission: WHERE tool_id=:tool_id: AND permission_flag=:permission_flag:');
                $QreadData->bindRaw(':table_vis2_permission:', $this->object->getJSONStringValue('database_prefix') . 'vis2_permission');
                $QreadData->bindInt(':tool_id:', $tool_id);
                $QreadData->bindString(':permission_flag:', $permission['permission_flag']);
                $QreadData->execute();
                if ($QreadData->rowCount() === 0) {
                    $QinsertData = new \osWFrame\Core\Database();
                    $QinsertData->prepare('INSERT INTO :table_vis2_permission: (tool_id, permission_flag, permission_title, permission_ispublic, permission_create_time, permission_create_user_id, permission_update_time, permission_update_user_id) VALUES (:tool_id:, :permission_flag:, :permission_title:, :permission_ispublic:, :permission_create_time:, :permission_create_user_id:, :permission_update_time:, :permission_update_user_id:)');
                    $QinsertData->bindRaw(':table_vis2_permission:', $this->object->getJSONStringValue('database_prefix') . 'vis2_permission');
                    $QinsertData->bindString(':permission_flag:', $permission['permission_flag']);
                    $QinsertData->bindString(':permission_title:', $permission['permission_title']);
                    $QinsertData->bindInt(':permission_ispublic:', $permission['permission_ispublic']);
                    $QinsertData->bindInt(':permission_create_time:', $time);
                    $QinsertData->bindInt(':permission_create_user_id:', $this->user_id);
                    $QinsertData->bindInt(':permission_update_time:', $time);
                    $QinsertData->bindInt(':permission_update_user_id:', $this->user_id);
                    $QinsertData->bindInt(':tool_id:', $tool_id);
                    $QinsertData->execute();
                } else {
                    $_result = $QreadData->fetch();
                    $QupdateData = new \osWFrame\Core\Database();
                    $QupdateData->prepare('UPDATE :table_vis2_permission: SET tool_id=:tool_id:, permission_flag=:permission_flag:, permission_title=:permission_title:, permission_ispublic=:permission_ispublic: WHERE permission_id=:permission_id:');
                    $QupdateData->bindRaw(':table_vis2_permission:', $this->object->getJSONStringValue('database_prefix') . 'vis2_permission');
                    $QupdateData->bindString(':permission_flag:', $permission['permission_flag']);
                    $QupdateData->bindString(':permission_title:', $permission['permission_title']);
                    $QupdateData->bindInt(':permission_ispublic:', $permission['permission_ispublic']);
                    $QupdateData->bindInt(':tool_id:', $tool_id);
                    $QupdateData->bindInt(':permission_id:', $_result['permission_id']);
                    $QupdateData->execute();
                    if ($QupdateData->rowCount() > 0) {
                        $QupdateData = new \osWFrame\Core\Database();
                        $QupdateData->prepare('UPDATE :table_vis2_permission: SET permission_update_time=:permission_update_time:, permission_update_user_id=:permission_update_user_id: WHERE permission_id=:permission_id:');
                        $QupdateData->bindRaw(':table_vis2_permission:', $this->object->getJSONStringValue('database_prefix') . 'vis2_permission');
                        $QupdateData->bindInt(':permission_update_time:', $time);
                        $QupdateData->bindInt(':permission_update_user_id:', $this->user_id);
                        $QupdateData->bindInt(':permission_id:', $_result['permission_id']);
                        $QupdateData->execute();
                    }
                }
            }

            /**
             * User-Tool
             */
            $QreadData = new \osWFrame\Core\Database();
            $QreadData->prepare('SELECT * FROM :table_vis2_user_tool: WHERE user_id=:user_id: AND tool_id=:tool_id:');
            $QreadData->bindRaw(':table_vis2_user_tool:', $this->object->getJSONStringValue('database_prefix') . 'vis2_user_tool');
            $QreadData->bindInt(':user_id:', $this->user_id);
            $QreadData->bindInt(':tool_id:', $tool_id);
            $QreadData->execute();
            if ($QreadData->rowCount() === 0) {
                $QinsertData = new \osWFrame\Core\Database();
                $QinsertData->prepare('INSERT INTO :table_vis2_user_tool: (user_id, tool_id, user_tool_create_time, user_tool_create_user_id, user_tool_update_time, user_tool_update_user_id) VALUES (:user_id:, :tool_id:, :user_tool_create_time:, :user_tool_create_user_id:, :user_tool_update_time:, :user_tool_update_user_id:)');
                $QinsertData->bindRaw(':table_vis2_user_tool:', $this->object->getJSONStringValue('database_prefix') . 'vis2_user_tool');
                $QinsertData->bindInt(':user_id:', $this->user_id);
                $QinsertData->bindInt(':tool_id:', $tool_id);
                $QinsertData->bindInt(':user_tool_create_time:', $time);
                $QinsertData->bindInt(':user_tool_create_user_id:', $this->user_id);
                $QinsertData->bindInt(':user_tool_update_time:', $time);
                $QinsertData->bindInt(':user_tool_update_user_id:', $this->user_id);
                $QinsertData->execute();
            }

            /**
             * User-Group
             */
            foreach ($ar_groups as $group_id) {
                $QreadData = new \osWFrame\Core\Database();
                $QreadData->prepare('SELECT * FROM :table_vis2_user_group: WHERE user_id=:user_id: AND group_id=:group_id: AND tool_id=:tool_id:');
                $QreadData->bindRaw(':table_vis2_user_group:', $this->object->getJSONStringValue('database_prefix') . 'vis2_user_group');
                $QreadData->bindInt(':user_id:', $this->user_id);
                $QreadData->bindInt(':group_id:', $group_id);
                $QreadData->bindInt(':tool_id:', $tool_id);
                $QreadData->execute();
                if ($QreadData->rowCount() === 0) {
                    $QinsertData = new \osWFrame\Core\Database();
                    $QinsertData->prepare('INSERT INTO :table_vis2_user_group: (user_id, group_id, tool_id, user_group_create_time, user_group_create_user_id, user_group_update_time, user_group_update_user_id) VALUES (:user_id:, :group_id:, :tool_id:, :user_group_create_time:, :user_group_create_user_id:, :user_group_update_time:, :user_group_update_user_id:)');
                    $QinsertData->bindRaw(':table_vis2_user_group:', $this->object->getJSONStringValue('database_prefix') . 'vis2_user_group');
                    $QinsertData->bindInt(':user_id:', $this->user_id);
                    $QinsertData->bindInt(':group_id:', $group_id);
                    $QinsertData->bindInt(':tool_id:', $tool_id);
                    $QinsertData->bindInt(':user_group_create_time:', $time);
                    $QinsertData->bindInt(':user_group_create_user_id:', $this->user_id);
                    $QinsertData->bindInt(':user_group_update_time:', $time);
                    $QinsertData->bindInt(':user_group_update_user_id:', $this->user_id);
                    $QinsertData->execute();
                }
            }

            $_pages = [];
            $_navigation = [];
            foreach ($_vis2_script['navigation'] as $navigation) {
                /**
                 * Page
                 */
                $QreadData = new \osWFrame\Core\Database();
                $QreadData->prepare('SELECT page_id FROM :table_vis2_page: WHERE page_name_intern=:page_name_intern: AND tool_id=:tool_id:');
                $QreadData->bindRaw(':table_vis2_page:', $this->object->getJSONStringValue('database_prefix') . 'vis2_page');
                $QreadData->bindString(':page_name_intern:', $navigation['page']['page_name_intern']);
                $QreadData->bindInt(':tool_id:', $tool_id);
                $QreadData->execute();
                if ($QreadData->rowCount() === 0) {
                    $QinsertData = new \osWFrame\Core\Database();
                    $QinsertData->prepare('INSERT INTO :table_vis2_page: (tool_id, page_name_intern, page_name, page_description, page_ispublic, page_create_time, page_create_user_id, page_update_time, page_update_user_id) VALUES (:tool_id:, :page_name_intern:, :page_name:, :page_description:, :page_ispublic:, :page_create_time:, :page_create_user_id:, :page_update_time:, :page_update_user_id:)');
                    $QinsertData->bindRaw(':table_vis2_page:', $this->object->getJSONStringValue('database_prefix') . 'vis2_page');
                    $QinsertData->bindInt(':tool_id:', $tool_id);
                    $QinsertData->bindString(':page_name:', $navigation['page']['page_name']);
                    $QinsertData->bindString(':page_name_intern:', $navigation['page']['page_name_intern']);
                    $QinsertData->bindString(':page_description:', $navigation['page']['page_description']);
                    $QinsertData->bindInt(':page_ispublic:', $navigation['page']['page_ispublic']);
                    $QinsertData->bindInt(':page_create_time:', $time);
                    $QinsertData->bindInt(':page_create_user_id:', $this->user_id);
                    $QinsertData->bindInt(':page_update_time:', $time);
                    $QinsertData->bindInt(':page_update_user_id:', $this->user_id);
                    $QinsertData->execute();
                    $_pages[$navigation['page']['page_name_intern']] = $QreadData->lastInsertId();
                } else {
                    $_result = $QreadData->fetch();
                    $QupdateData = new \osWFrame\Core\Database();
                    $QupdateData->prepare('UPDATE :table_vis2_page: SET tool_id=:tool_id:, page_name=:page_name:, page_description=:page_description:, page_ispublic=:page_ispublic: WHERE page_id=:page_id:');
                    $QupdateData->bindRaw(':table_vis2_page:', $this->object->getJSONStringValue('database_prefix') . 'vis2_page');
                    $QupdateData->bindInt(':tool_id:', $tool_id);
                    $QupdateData->bindString(':page_name:', $navigation['page']['page_name']);
                    $QupdateData->bindString(':page_description:', $navigation['page']['page_description']);
                    $QupdateData->bindInt(':page_ispublic:', $navigation['page']['page_ispublic']);
                    $QupdateData->bindInt(':user_status:', 1);
                    $QupdateData->bindInt(':page_id:', $_result['page_id']);
                    $QupdateData->execute();
                    if ($QupdateData->rowCount() > 0) {
                        $QupdateData = new \osWFrame\Core\Database();
                        $QupdateData->prepare('UPDATE :table_vis2_page: SET page_update_time=:page_update_time:, page_update_user_id=:page_update_user_id: WHERE page_id=:page_id:');
                        $QupdateData->bindRaw(':table_vis2_page:', $this->object->getJSONStringValue('database_prefix') . 'vis2_page');
                        $QupdateData->bindInt(':page_update_time:', $time);
                        $QupdateData->bindInt(':page_update_user_id:', $this->user_id);
                        $QupdateData->bindInt(':page_id:', $_result['page_id']);
                        $QupdateData->execute();
                    }
                    $_pages[$navigation['page']['page_name_intern']] = $_result['page_id'];
                }

                foreach ($navigation['page']['permission'] as $permission) {
                    /**
                     * Page-Permission
                     */
                    $QreadData = new \osWFrame\Core\Database();
                    $QreadData->prepare('SELECT page_id FROM :table_vis2_page_permission: WHERE page_id=:page_id: AND tool_id=:tool_id: AND permission_flag=:permission_flag:');
                    $QreadData->bindRaw(':table_vis2_page_permission:', $this->object->getJSONStringValue('database_prefix') . 'vis2_page_permission');
                    $QreadData->bindInt(':page_id:', $_pages[$navigation['page']['page_name_intern']]);
                    $QreadData->bindInt(':tool_id:', $tool_id);
                    $QreadData->bindString(':permission_flag:', $permission);
                    $QreadData->execute();
                    if ($QreadData->rowCount() === 0) {
                        $QinsertData = new \osWFrame\Core\Database();
                        $QinsertData->prepare('INSERT INTO :table_vis2_page_permission: (page_id, tool_id, permission_flag, page_permission_create_time, page_permission_create_user_id, page_permission_update_time, page_permission_update_user_id) VALUES (:page_id:, :tool_id:, :permission_flag:, :page_permission_create_time:, :page_permission_create_user_id:, :page_permission_update_time:, :page_permission_update_user_id:)');
                        $QinsertData->bindRaw(':table_vis2_page_permission:', $this->object->getJSONStringValue('database_prefix') . 'vis2_page_permission');
                        $QinsertData->bindInt(':page_id:', $_pages[$navigation['page']['page_name_intern']]);
                        $QinsertData->bindInt(':tool_id:', $tool_id);
                        $QinsertData->bindString(':permission_flag:', $permission);
                        $QinsertData->bindInt(':page_permission_create_time:', $time);
                        $QinsertData->bindInt(':page_permission_create_user_id:', $this->user_id);
                        $QinsertData->bindInt(':page_permission_update_time:', $time);
                        $QinsertData->bindInt(':page_permission_update_user_id:', $this->user_id);
                        $QinsertData->execute();
                    }
                }

                foreach ($navigation['permission'] as $group_id => $group_permission) {
                    foreach ($group_permission as $permission) {
                        /**
                         * Page-Permission
                         */
                        $QreadData = new \osWFrame\Core\Database();
                        $QreadData->prepare('SELECT * FROM :table_vis2_group_permission: WHERE group_id=:group_id: AND permission_page=:permission_page: AND permission_flag=:permission_flag:');
                        $QreadData->bindRaw(':table_vis2_group_permission:', $this->object->getJSONStringValue('database_prefix') . 'vis2_group_permission');
                        $QreadData->bindInt(':group_id:', $ar_groups[$group_id]);
                        $QreadData->bindString(':permission_page:', $navigation['page']['page_name_intern']);
                        $QreadData->bindString(':permission_flag:', $permission);
                        $QreadData->execute();
                        if ($QreadData->rowCount() === 0) {
                            $QinsertData = new \osWFrame\Core\Database();
                            $QinsertData->prepare('INSERT INTO :table_vis2_group_permission: (group_id, permission_page, permission_flag, group_permission_create_time, group_permission_create_user_id, group_permission_update_time, group_permission_update_user_id) VALUES (:group_id:, :permission_page:, :permission_flag:, :group_permission_create_time:, :group_permission_create_user_id:, :group_permission_update_time:, :group_permission_update_user_id:)');
                            $QinsertData->bindRaw(':table_vis2_group_permission:', $this->object->getJSONStringValue('database_prefix') . 'vis2_group_permission');
                            $QinsertData->bindInt(':group_id:', $ar_groups[$group_id]);
                            $QinsertData->bindString(':permission_page:', $navigation['page']['page_name_intern']);
                            $QinsertData->bindString(':permission_flag:', $permission);
                            $QinsertData->bindInt(':group_permission_create_time:', $time);
                            $QinsertData->bindInt(':group_permission_create_user_id:', $this->user_id);
                            $QinsertData->bindInt(':group_permission_update_time:', $time);
                            $QinsertData->bindInt(':group_permission_update_user_id:', $this->user_id);
                            $QinsertData->execute();
                        }
                    }
                }

                /**
                 * Navigation
                 */
                $QreadData = new \osWFrame\Core\Database();
                $QreadData->prepare('SELECT * FROM :table_vis2_navigation: WHERE tool_id=:tool_id: AND page_id=:page_id:');
                $QreadData->bindRaw(':table_vis2_navigation:', $this->object->getJSONStringValue('database_prefix') . 'vis2_navigation');
                $QreadData->bindInt(':tool_id:', $tool_id);
                $QreadData->bindInt(':page_id:', $_pages[$navigation['page']['page_name_intern']]);
                $QreadData->execute();
                if ($QreadData->rowCount() === 0) {
                    $QinsertData = new \osWFrame\Core\Database();
                    $QinsertData->prepare('INSERT INTO :table_vis2_navigation: (tool_id, page_id, navigation_parent_id, navigation_title, navigation_sortorder, navigation_ispublic, navigation_create_time, navigation_create_user_id, navigation_update_time, navigation_update_user_id) VALUES (:tool_id:, :page_id:, :navigation_parent_id:, :navigation_title:, :navigation_sortorder:, :navigation_ispublic:, :navigation_create_time:, :navigation_create_user_id:, :navigation_update_time:, :navigation_update_user_id:)');
                    $QinsertData->bindRaw(':table_vis2_navigation:', $this->object->getJSONStringValue('database_prefix') . 'vis2_navigation');
                    $QinsertData->bindInt(':tool_id:', $tool_id);
                    $QinsertData->bindInt(':page_id:', $_pages[$navigation['page']['page_name_intern']]);
                    if (isset($_navigation[$navigation['navigation_parent_id']])) {
                        $QinsertData->bindInt(':navigation_parent_id:', $_navigation[$navigation['navigation_parent_id']]);
                    } else {
                        $QinsertData->bindInt(':navigation_parent_id:', 0);
                    }
                    $QinsertData->bindString(':navigation_title:', $navigation['navigation_title']);
                    $QinsertData->bindInt(':navigation_sortorder:', $navigation['navigation_sortorder']);
                    $QinsertData->bindInt(':navigation_ispublic:', $navigation['navigation_ispublic']);
                    $QinsertData->bindInt(':navigation_create_time:', $time);
                    $QinsertData->bindInt(':navigation_create_user_id:', $this->user_id);
                    $QinsertData->bindInt(':navigation_update_time:', $time);
                    $QinsertData->bindInt(':navigation_update_user_id:', $this->user_id);
                    $QinsertData->execute();
                    $_navigation[$navigation['page']['page_name_intern']] = $QinsertData->lastInsertId();
                } else {
                    $_result = $QreadData->fetch();
                    $QupdateData = new \osWFrame\Core\Database();
                    $QupdateData->prepare('UPDATE :table_vis2_navigation: SET navigation_title=:navigation_title:, navigation_sortorder=:navigation_sortorder:, navigation_ispublic=:navigation_ispublic: WHERE tool_id=:tool_id: AND page_id=:page_id:');
                    $QupdateData->bindRaw(':table_vis2_navigation:', $this->object->getJSONStringValue('database_prefix') . 'vis2_navigation');
                    $QupdateData->bindString(':navigation_title:', $navigation['navigation_title']);
                    $QupdateData->bindInt(':navigation_sortorder:', $navigation['navigation_sortorder']);
                    $QupdateData->bindInt(':navigation_ispublic:', $navigation['navigation_ispublic']);
                    $QupdateData->bindInt(':tool_id:', $tool_id);
                    $QupdateData->bindInt(':page_id:', $_pages[$navigation['page']['page_name_intern']]);
                    $QupdateData->execute();
                    if ($QupdateData->rowCount() > 0) {
                        $QupdateData = new \osWFrame\Core\Database();
                        $QupdateData->prepare('UPDATE :table_vis2_navigation: SET navigation_update_time=:navigation_update_time:, navigation_update_user_id=:navigation_update_user_id: WHERE navigation_id=:navigation_id:');
                        $QupdateData->bindRaw(':table_vis2_navigation:', $this->object->getJSONStringValue('database_prefix') . 'vis2_navigation');
                        $QupdateData->bindInt(':navigation_update_time:', $time);
                        $QupdateData->bindInt(':navigation_update_user_id:', $this->user_id);
                        $QupdateData->bindInt(':navigation_id:', $_result['navigation_id']);
                        $QupdateData->execute();
                    }
                    $_navigation[$navigation['page']['page_name_intern']] = $_result['navigation_id'];
                }
            }
        }

        return true;
    }
}
