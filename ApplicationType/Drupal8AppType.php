<?php

namespace DigipolisGent\Domainator9k\AppTypes\DrupalEightBundle\ApplicationType;

use DigipolisGent\Domainator9k\CoreBundle\Entity\BaseAppType;
use DigipolisGent\Domainator9k\AppTypes\DrupalEightBundle\Entity\DrupalEightSettings;
use DigipolisGent\Domainator9k\AppTypes\DrupalEightBundle\Form\DrupalEightSettingsType;
use DigipolisGent\Domainator9k\CoreBundle\Entity\Settings;
use DigipolisGent\Domainator9k\CoreBundle\Entity\AppEnvironment;

class Drupal8AppType extends BaseAppType
{
    protected $settingsFormClass = DrupalEightSettingsType::class;
    protected $settingsEntityClass = DrupalEightSettings::class;

    public function getConfigFiles(AppEnvironment $env, array $servers, Settings $settings)
    {
        $user = $env->getServerSettings()->getUser();

        $dbName = $env->getDatabaseSettings()->getName();
        $dbUser = $env->getDatabaseSettings()->getUser();
        $dbPass = $env->getDatabaseSettings()->getPassword();
        $dbHost = $env->getDatabaseSettings()->getHost();
        $redisPass = $settings->getAppEnvironmentSettings($env)->getRedisPassword();
        $config = $env->getSiteConfig();
        $appFolder = $env->getApplication()->getNameForFolder();
        $appName = $env->getApplication()->getNameCanonical();
        $salt = $env->getSalt();

        if ($this->getAppTypeSettingsService()->getSettings($env->getApplication(), false)) {
            $installProfile = $this->getAppTypeSettingsService()->getSettings($env->getApplication(), false)->getInstallProfile();
        } else {
            $installProfile = $env->getApplication()->getAppTypeSettings()->getInstallProfile();
        }

        $content = <<<SETTINGS
<?php
\$databases['default']['default'] = array (
  'database' => '$dbName',
  'username' => '$dbUser',
  'password' => '$dbPass',
  'host' => '$dbHost',
  'prefix' => '',
  'port' => '',
  'namespace' => 'Drupal\\\\Core\\\\Database\\\\Driver\\\\mysql',
  'driver' => 'mysql',
);
\$settings['hash_salt'] = '$salt';
\$settings['file_public_path'] = 'sites/default/files';
\$settings['file_private_path'] = '/home/$user/apps/$appFolder/files/private';
\$config['system.file']['path']['temporary'] = '/home/$user/apps/$appFolder/files/tmp';
if (file_exists('modules/contrib/redis/redis.info.yml') && !file_exists('/var/run/redis/redis_isnotavailable')) {
  \$settings['redis.connection']['interface'] = 'PhpRedis';
  \$settings['redis.connection']['host']      = '127.0.0.1';
  \$settings['redis.connection']['port']      = '6380';
  \$settings['container_yamls'][] = 'modules/redis/example.services.yml';
  \$settings['cache']['default'] = 'cache.backend.redis';
  \$settings['cache']['bins']['bootstrap'] = 'cache.backend.chainedfast';
  \$settings['cache']['bins']['discovery'] = 'cache.backend.chainedfast';
  \$settings['cache']['bins']['config'] = 'cache.backend.chainedfast';
  \$settings['cache']['default'] = 'cache.backend.redis';
  \$settings['cache_prefix'] = '$appName';
  \$settings['redis.connection']['password'] = '$redisPass';
  // TODO \$conf['cache_class_cache_form']  = 'DrupalDatabaseCache';
  // TODO \$conf['redis_client_timeout']    = 2;
  // TODO \$conf['redis_client_reserved']   = NULL;
  // TODO \$conf['redis_client_retry_interval'] = 200;
}
\$config_directories['sync'] = '../config/sync';
\$settings['install_profile'] = '$installProfile';
\$config['google_analytics.settings']['account'] = '';
$config
SETTINGS;

        $content = $env->replaceConfigPlaceholders($content);
        $files = array(
            '/dist/'.$user.'/'.$appFolder.'/config/settings.php' => $content,
        );

        $aliases = '<?php';

        $environments = $this->getEnvironmentService()->getEnvironments();

        foreach ($environments as $environment) {
            // TODO: This shouldn't be necessary. Probably because a Filesystem init only gets env servers, this is otherwise empty for "remote envs"
            $ip = '';

            foreach ($servers as $server) {
                if ($server->isTaskServer() && $server->getEnvironment() === $environment) {
                    $ip = $server->getIp();
                    break;
                }
            }

            $uri = $env->getApplication()->getAppEnvironment($environment)->getPreferredDomain();

            if ($env->getName() === $environment) {
                $aliases .= <<<ALIAS

\$aliases['$environment'] = array(
    'uri' => '$uri',
    'root' => '/home/$user/apps/$appFolder/current',
);
ALIAS;
            } else {
                $aliases .= <<<ALIAS

\$aliases['$environment'] = array(
    'uri' => '$uri',
    'root' => '/home/$user/apps/$appFolder/current',
    'remote-host' => '$ip',
    'remote-user' => '$user',
);
ALIAS;
            }

            $files['/home/'.$user.'/.drush/'.(($appFolder !== 'default') ? $appFolder : $env->getName()).'.aliases.drushrc.php'] = $aliases;
        }

        return $files;
    }
}
