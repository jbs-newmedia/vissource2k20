<?php declare(strict_types=0);

/**
 * This file is part of the VIS2 package
 *
 * @author    Juergen Schwind
 * @copyright Copyright (c) JBS New Media GmbH - Juergen Schwind (https://jbs-newmedia.com)
 * @package   VIS2
 * @link      https://oswframe.com
 * @license   MIT License
 */

namespace VIS2\Core;

use osWFrame\Core\BaseConnectionTrait;
use osWFrame\Core\BaseStaticTrait;
use osWFrame\Core\BaseTemplateBridgeTrait;
use osWFrame\Core\BaseVarTrait;
use osWFrame\Core\Filesystem;
use osWFrame\Core\Resource;
use osWFrame\Core\Session;
use osWFrame\Core\Settings;
use osWFrame\Core\Template;

class Main
{
    use BaseStaticTrait;
    use BaseConnectionTrait;
    use BaseVarTrait;
    use BaseTemplateBridgeTrait;

    /**
     * Major-Version der Klasse.
     */
    private const CLASS_MAJOR_VERSION = 2;

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

    protected string $tool = '';

    protected array $tools = [];

    public function __construct()
    {
    }

    public static function getResourcePath(): string
    {
        $version = self::getVersion();
        $dir = strtolower('VIS2');

        return Resource::getRelDir() . $dir . \DIRECTORY_SEPARATOR . $version . \DIRECTORY_SEPARATOR;
    }

    /**
     * @return $this
     */
    public function setEnvironment(Template $Template): self
    {
        $version = self::getVersion();
        $dir = strtolower('VIS2');
        $name = $version . '.resource';
        $path = self::getResourcePath();
        if (Resource::existsResource('VIS2', $name) !== true) {
            $files = [
                'js' . \DIRECTORY_SEPARATOR . 'vis2.js',
                'css' . \DIRECTORY_SEPARATOR . 'vis2.css',
                'img' . \DIRECTORY_SEPARATOR . 'profile.png',
            ];

            Resource::copyResourcePath(
                'oswcore' . \DIRECTORY_SEPARATOR . 'modules' . \DIRECTORY_SEPARATOR . 'vis2' . \DIRECTORY_SEPARATOR,
                $dir . \DIRECTORY_SEPARATOR . $version . \DIRECTORY_SEPARATOR,
                $files
            );

            Resource::copyResourcePath(
                'oswproject' . \DIRECTORY_SEPARATOR . 'modules' . \DIRECTORY_SEPARATOR . 'vis2' . \DIRECTORY_SEPARATOR,
                $dir . \DIRECTORY_SEPARATOR . $version . \DIRECTORY_SEPARATOR,
                $files
            );
            Resource::writeResource('VIS2', $name, 'time:' . time());
        }

        $this->setTemplate($Template);

        $this->addTemplateJSFile('head', $path . 'js' . \DIRECTORY_SEPARATOR . 'vis2.js');
        $this->addTemplateCSSFile('head', $path . 'css' . \DIRECTORY_SEPARATOR . 'vis2.css');

        return $this;
    }

    public function getResourceLink(string $file): string
    {
        $version = self::getVersion();
        $dir = strtolower('VIS2');
        $name = $version . '.resource';
        $path = self::getResourcePath();

        $rfile = Settings::getStringVar('settings_abspath') . $path . $file;
        $lfile = Settings::getStringVar(
                'settings_abspath'
            ) . 'modules' . \DIRECTORY_SEPARATOR . 'vis2' . \DIRECTORY_SEPARATOR . $file;

        if ((Filesystem::existsFile($rfile) !== true) || ((Filesystem::getFileModTime(
                    $rfile
                )) < (Filesystem::getFileModTime($lfile)))
        ) {
            Resource::copyResourcePath(
                'oswcore' . \DIRECTORY_SEPARATOR . 'modules' . \DIRECTORY_SEPARATOR . 'vis2' . \DIRECTORY_SEPARATOR,
                $dir . \DIRECTORY_SEPARATOR . $version . \DIRECTORY_SEPARATOR,
                [$file]
            );
            Resource::copyResourcePath(
                'oswproject' . \DIRECTORY_SEPARATOR . 'modules' . \DIRECTORY_SEPARATOR . 'vis2' . \DIRECTORY_SEPARATOR,
                $dir . \DIRECTORY_SEPARATOR . $version . \DIRECTORY_SEPARATOR,
                [$file]
            );
        }

        return $path . $file;
    }

    /*
     * Setzt das aktuelle Tool.
     *
     * @param string $tool
     * @return boolean
     */
    public function setTool(string $tool): bool
    {
        $tool = strtolower($tool);
        if ($this->validateTool($tool) === true) {
            $this->tool = $tool;
            Session::setStringVar('vis2_tool', $this->tool);
            $this->loadToolDetails();

            return true;
        }

        return false;
    }

    /*
     * Liefert das aktuelle Tool.
     *
     * @return string
     */
    public function getTool(): string
    {
        return $this->tool;
    }

    /*
     * Ermittelt die vorhanden Tools.
     *
     * @param bool $force
     * @return $this
     */
    public function loadTools(): self
    {
        $this->tools === [];
        $this->tools[Settings::getStringVar('vis2_login_module')] = 'Login';
        $this->tools[Settings::getStringVar('vis2_logout_module')] = 'Logout';
        $this->tools[Settings::getStringVar('vis2_chtool_module')] = 'ChTool';

        $QselectTools = self::getConnection();
        $QselectTools->prepare(
            'SELECT * FROM :table_vis2_tool: WHERE tool_ispublic=:tool_ispublic: ORDER BY tool_name ASC'
        );
        $QselectTools->bindTable(':table_vis2_tool:', 'vis2_tool');
        $QselectTools->bindInt(':tool_ispublic:', 1);
        foreach ($QselectTools->query() as $tool) {
            $this->tools[$tool['tool_name_intern']] = $tool['tool_name'];
        }

        return $this;
    }

    /*
     * Ermittelt die vorhanden Tools.
     *
     * @param bool $force
     * @return array
     */
    public function getTools(): array
    {
        if ($this->tools === []) {
            $this->loadTools();
        }

        return $this->tools;
    }

    /*
     * Ermittelt Details des Tools.
     *
     * @param string $key
     * @return bool
     */
    public function loadToolDetails(): bool
    {
        $this->clearVars();

        if (\in_array(
            $this->getTool(),
            [
                Settings::getStringVar('vis2_login_module'),
                Settings::getStringVar('vis2_logout_module'),
                Settings::getStringVar('vis2_chtool_module'),
            ],
            true
        )
        ) {
            if ($this->getTool() === Settings::getStringVar('vis2_login_module')) {
                $this->vars = [
                    'tool_id' => 0,
                    'tool_name_intern' => Settings::getStringVar('vis2_login_module'),
                    'tool_name' => 'Anmelden',
                    'tool_description' => 'Anmelden',
                    'tool_ispublic' => 1,
                    'tool_hide_logon' => 0,
                    'tool_hide_navigation' => 0,
                    'tool_use_mandant' => 0,
                    'tool_use_mandantswitch' => 0,
                    'tool_create_time' => 0,
                    'tool_create_user_id' => 0,
                    'tool_update_time' => 0,
                    'tool_update_user_id' => 0,
                ];
            }
            if ($this->getTool() === Settings::getStringVar('vis2_logout_module')) {
                $this->vars = [
                    'tool_id' => 0,
                    'tool_name_intern' => Settings::getStringVar('vis2_logout_module'),
                    'tool_name' => 'Abmelden',
                    'tool_description' => 'Abmelden',
                    'tool_ispublic' => 1,
                    'tool_hide_logon' => 0,
                    'tool_hide_navigation' => 0,
                    'tool_use_mandant' => 0,
                    'tool_use_mandantswitch' => 0,
                    'tool_create_time' => 0,
                    'tool_create_user_id' => 0,
                    'tool_update_time' => 0,
                    'tool_update_user_id' => 0,
                ];
            }
            if ($this->getTool() === Settings::getStringVar('vis2_chtool_module')) {
                $this->vars = [
                    'tool_id' => 0,
                    'tool_name_intern' => Settings::getStringVar('vis2_chtool_module'),
                    'tool_name' => 'Programm wählen',
                    'tool_description' => 'Programm wählen',
                    'tool_ispublic' => 1,
                    'tool_hide_logon' => 0,
                    'tool_hide_navigation' => 0,
                    'tool_use_mandant' => 0,
                    'tool_use_mandantswitch' => 0,
                    'tool_create_time' => 0,
                    'tool_create_user_id' => 0,
                    'tool_update_time' => 0,
                    'tool_update_user_id' => 0,
                ];
            }
        } else {
            $QgetToolDetails = self::getConnection();
            $QgetToolDetails->prepare('SELECT * FROM :table_vis2_tool: WHERE tool_name_intern=:tool_name_intern:');
            $QgetToolDetails->bindTable(':table_vis2_tool:', 'vis2_tool');
            $QgetToolDetails->bindString(':tool_name_intern:', $this->getTool());
            if ($QgetToolDetails->exec() === 1) {
                $this->vars = $QgetToolDetails->fetch();

                return true;
            }
        }

        return false;
    }

    public function getToolDetails(): array
    {
        if ($this->isVarsLoaded() !== true) {
            $this->loadToolDetails();
        }

        return $this->vars;
    }

    /*
     * Liefert die aktuelle ToolId
     *
     * @return int|null
     */
    public function getToolId(): ?int
    {
        return $this->getIntVar('tool_id');
    }

    /*
     * Liefert den aktuellen ToolName
     *
     * @return int|null
     */
    public function getToolName(): ?string
    {
        return $this->getStringVar('tool_name');
    }

    /*
     * Überprueft ob es sich um ein gültiges Modul handelt.
     *
     * @param string $tool
     * @return bool
     */
    protected function validateTool(string $tool): bool
    {
        if ($this->tools === []) {
            $this->loadTools();
        }
        if (isset($this->tools[$tool])) {
            return true;
        }

        return false;
    }
}
