<?php declare(strict_types=1);

namespace Storage\Service;


use MongoDB\Client;
use Phalcon\Config;
use Phalcon\Di;

trait DiTrait
{
    private function setContainer(string $type): void
    {
        $this->getDI()->setShared('config', function () {
            $config = new Config(include SERVICE_ROOT_DIR . '/etc/config.php');

            if (file_exists(SERVICE_ROOT_DIR . '/etc/config-local.php')) {
                $localConfig = new Config(include SERVICE_ROOT_DIR . '/etc/config-local.php');

                $config->merge($localConfig);
            }

            return $config;
        });

        $this->getDI()->loadFromPhp(SERVICE_ROOT_DIR . '/etc/container/' . $type . '.php');
        if (file_exists(SERVICE_ROOT_DIR . '/etc/container/' . $type . '-local.php')) {
            $this->getDI()->loadFromPhp(SERVICE_ROOT_DIR . '/etc/container/' . $type . '-local.php');
        }

        $parent = $this;
        $this->getDI()->setShared('mongodb', function () use ($parent) {
            return $parent->getDI()->get(Client::class)->selectDatabase(
                $parent->getDI()->get('config')->path('mongodb.dbname')
            );
        });
    }

    /**
     * @example this is method us only in config file
     */
    public static function getConfig()
    {
        if (null === Di::getDefault()) {
            return null;
        }

        $args = func_get_args();
        $config = Di::getDefault()->getShared('config');

        if (empty($args)) {
            return $config;
        }

        return call_user_func_array(
            [$config, 'path'],
            $args
        );
    }
}
