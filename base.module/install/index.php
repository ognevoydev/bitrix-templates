<?php

use Bitrix\Main\Application;
use Bitrix\Main\IO\File;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;

Loc::loadMessages(__FILE__);

class base_module extends CModule
{

    var $MODULE_ID = "base.module";
    var $MODULE_VERSION;
    var $MODULE_VERSION_DATE;
    var $MODULE_NAME;
    var $MODULE_DESCRIPTION;

    var string $COMPONENTS_FROM_PATH;
    var string $COMPONENTS_TO_PATH;
    var string $PUBLIC_FROM_PATH;
    var string $PUBLIC_TO_PATH;

    function __construct()
    {
        $arModuleVersion = array();
        include(__DIR__ . "/version.php");

        $this->MODULE_VERSION = $arModuleVersion["VERSION"];
        $this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
        $this->MODULE_NAME = Loc::getMessage("BM_NAME");
        $this->MODULE_DESCRIPTION = Loc::getMessage("BM_DESC");

        $this->COMPONENTS_FROM_PATH = $_SERVER["DOCUMENT_ROOT"] . "/local/modules/" . $this->MODULE_ID . "/install/components/";
        $this->COMPONENTS_TO_PATH = $_SERVER["DOCUMENT_ROOT"] . "/bitrix/components/" . $this->MODULE_ID . "/";
        $this->PUBLIC_FROM_PATH = $_SERVER["DOCUMENT_ROOT"] . "/local/modules/" . $this->MODULE_ID . "/install/public/";
        $this->PUBLIC_TO_PATH = $_SERVER["DOCUMENT_ROOT"] . "/";
    }

    public function DoInstall()
    {
        $this->installFiles();
        ModuleManager::registerModule($this->MODULE_ID);
    }

    public function DoUninstall()
    {
        $this->unInstallFiles();
        ModuleManager::unRegisterModule($this->MODULE_ID);
    }

    public function installFiles()
    {
        if (\Bitrix\Main\IO\Directory::isDirectoryExists($this->COMPONENTS_FROM_PATH)) {
            CopyDirFiles($this->COMPONENTS_FROM_PATH, $this->COMPONENTS_TO_PATH, true, true);
        }

        if (\Bitrix\Main\IO\Directory::isDirectoryExists($this->PUBLIC_FROM_PATH)) {
            CopyDirFiles($this->PUBLIC_FROM_PATH, $this->PUBLIC_TO_PATH, true, true);
        }
    }

    public function unInstallFiles()
    {
        $this->deleteFiles($this->COMPONENTS_FROM_PATH, $this->COMPONENTS_TO_PATH);
        $this->deleteFiles($this->PUBLIC_FROM_PATH, $this->PUBLIC_TO_PATH);
    }

    private function deleteFiles(string $installedFromPath, string $installedToPath)
    {
        $files = scandir($installedFromPath);
        if (($key = array_search(".", $files)) !== false) {
            unset($files[$key]);
        }
        if (($key = array_search("..", $files)) !== false) {
            unset($files[$key]);
        }
        foreach ($files as $file) {
            if (is_file($installedFromPath . $file)) {
                \Bitrix\Main\IO\File::deleteFile($installedToPath . $file);
            }
            if (is_dir($installedFromPath . $file)) {
                \Bitrix\Main\IO\Directory::deleteDirectory($installedToPath . $file);
            }
        }
    }

}
