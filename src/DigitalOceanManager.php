<?php

declare(strict_types=1);

/*
 * This file is part of Laravel DigitalOcean.
 *
 * (c) Graham Campbell <graham@alt-three.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PowerPBX\DigitalOcean;

use PowerPBX\Manager\AbstractManager;
use Illuminate\Contracts\Config\Repository;
use App\Models\RemoteApiToken;

/**
 * This is the digitalocean manager class.
 *
 * @method \DigitalOceanV2\Api\Action action()
 * @method \DigitalOceanV2\Api\Image image()
 * @method \DigitalOceanV2\Api\Domain domain()
 * @method \DigitalOceanV2\Api\DomainRecord domainRecord()
 * @method \DigitalOceanV2\Api\Size size()
 * @method \DigitalOceanV2\Api\Region region()
 * @method \DigitalOceanV2\Api\Key key()
 * @method \DigitalOceanV2\Api\Droplet droplet()
 * @method \DigitalOceanV2\Api\RateLimit rateLimit()
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
class DigitalOceanManager extends AbstractManager
{
    /**
     * The factory instance.
     *
     * @var \GrahamCampbell\DigitalOcean\DigitalOceanFactory
     */
    protected $factory;

    /**
     * Create a new digitalocean manager instance.
     *
     * @param \Illuminate\Contracts\Config\Repository          $config
     * @param \GrahamCampbell\DigitalOcean\DigitalOceanFactory $factory
     *
     * @return void
     */
    public function __construct(Repository $config, DigitalOceanFactory $factory)
    {
        parent::__construct($config);
        $this->factory = $factory;
    }

    /**
     * Create the connection instance.
     *
     * @param array $config
     *
     * @return \DigitalOceanV2\DigitalOceanV2
     */
    protected function createConnection(array $config)
    {
        $token = $this->getToken();
        if (!array_key_exists('token', $config)) {
            $config['token'] = $token;
        }
        
        return $this->factory->make($config);
    }
    
    protected function getToken() 
    {
        $tokenName = 'do_token';
        $id = auth()->user()->id;
        $collection = RemoteApiToken::where('user_id', $id)->get();
        $array =  $collection->map->only([$tokenName])->first();
        return $array[$tokenName];
    }

    /**
     * Get the configuration name.
     *
     * @return string
     */
    protected function getConfigName()
    {
        return 'digitalocean';
    }

    /**
     * Get the factory instance.
     *
     * @return \GrahamCampbell\DigitalOcean\DigitalOceanFactory
     */
    public function getFactory()
    {
        return $this->factory;
    }
}
