<?php

namespace DigipolisGent\Domainator9k\AppTypes\DrupalEightBundle\CacheClearer;

use DigipolisGent\CommandBuilder\CommandBuilder;
use DigipolisGent\Domainator9k\CoreBundle\CacheClearer\CacheClearerInterface;
use DigipolisGent\Domainator9k\CoreBundle\CLI\CliInterface;

class DrupalEightCacheClearer implements CacheClearerInterface
{

    /**
     * {@inheritdoc}
     */
    public function clearCache($object, CliInterface $cli)
    {
        return $cli->execute(
            CommandBuilder::create('drush')
                ->addArgument('cr')
        );
    }
}
