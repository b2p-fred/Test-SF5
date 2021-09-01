<?php

namespace App\Manager;

class ApiInformationManager
{
    public const CUSTOM_CONFIGURATION_FILE = '/config/custom_configuration_file.yml';

    private string $rootDir;

    private string $apiTitle;

    private string $apiDescription;

    private string $apiVersion;

    private string $legalNoticeLink;

    private string $onlineHelpLink;

    private string $defaultLanguage;

    /**
     * @param string $rootDir         The application root directory
     * @param string $apiTitle        The API title
     * @param string $apiDescription  The API description
     * @param string $apiVersion      The API version
     * @param string $legalNoticeLink The legal notice URI
     * @param string $onlineHelpLink  The online help URI
     * @param string $defaultLanguage The default language
     */
    public function __construct(
        string $rootDir,
        string $apiTitle,
        string $apiDescription,
        string $apiVersion,
        string $legalNoticeLink,
        string $onlineHelpLink,
        string $defaultLanguage
    ) {
        $this->rootDir = $rootDir;
        $this->apiTitle = $apiTitle;
        $this->apiDescription = $apiDescription;
        $this->apiVersion = $apiVersion;
        $this->legalNoticeLink = $legalNoticeLink;
        $this->onlineHelpLink = $onlineHelpLink;
        $this->defaultLanguage = $defaultLanguage;
    }

    public function getApiTitle(): string
    {
        return $this->apiTitle;
    }

    public function getRootDir(): string
    {
        return $this->rootDir;
    }

    public function getApiDescription(): string
    {
        return $this->apiDescription;
    }

    public function getApiVersion(): string
    {
        return $this->apiVersion;
    }

    public function getLegalNoticeLink(): string
    {
        return $this->legalNoticeLink;
    }

    public function getOnlineHelpLink(): string
    {
        return $this->onlineHelpLink;
    }

    public function getDefaultLanguage(): string
    {
        return $this->defaultLanguage;
    }

    public function getBaseConfiguration(): array
    {
        return [
//            'root_directory' => $this->getRootDir(),
            'apiTitle' => $this->getApiTitle(),
            'apiDescription' => $this->getApiDescription(),
            'apiVersion' => $this->getApiVersion(),
            'apiLegalNoticeUri' => $this->getLegalNoticeLink(),
            'onlineHelpUri' => $this->getOnlineHelpLink(),
            'defaultLanguage' => $this->getDefaultLanguage(),
        ];
    }
}
