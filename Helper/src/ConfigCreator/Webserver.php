<?php
declare(strict_types=1);

namespace App\ConfigCreator;

use App\Model\ProjectSettings;
use Symfony\Component\Filesystem\Filesystem;

class Webserver implements ConfigCreatorInterface
{

    private const SITE_CONF_PATH = '/etc/apache2/sites-enabled/';
    private const PHP_INI_PATH = '/usr/local/etc/php/';

    private Filesystem $filesystem;

    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    public function getName(): string
    {
        return 'webserver config files';
    }

    public function create(ProjectSettings $projectSettings): void
    {
        if (!$this->filesystem->exists(FilePath::RESOURCE_FOLDER . 'config/Webserver')) {
            throw new \Exception('Webserver config not found.');
        }

        $this->filesystem->mkdir($projectSettings->getTempPath() . '/config' . self::SITE_CONF_PATH);
        $this->filesystem->mkdir($projectSettings->getTempPath() . '/config' . self::PHP_INI_PATH);

        $this->filesystem->copy(
            FilePath::RESOURCE_FOLDER . 'config/Webserver/' . self::PHP_INI_PATH . 'php.ini',
            $projectSettings->getTempPath() . '/config/' . self::PHP_INI_PATH . 'php.ini'
        );

        $webHostConf = $projectSettings->getTempPath() . '/config/' . self::SITE_CONF_PATH . 'app.conf';
        $this->filesystem->touch($webHostConf);
        $virtualHostContent = file_get_contents(FilePath::RESOURCE_FOLDER . 'config/Webserver/' . self::SITE_CONF_PATH . '/app.conf');
        file_put_contents(
            $webHostConf,
            str_replace('<<HOSTNAME>>', $projectSettings->getHostname(), $virtualHostContent)
        );
    }
}
