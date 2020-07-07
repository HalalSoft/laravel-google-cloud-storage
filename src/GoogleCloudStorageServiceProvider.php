<?php

namespace Halalsoft\LaravelGoogleCloudStorage;

use Aws\S3\S3Client;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Filesystem\Cache;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Support\ServiceProvider;
use InvalidArgumentException;
use League\Flysystem\AdapterInterface;
use League\Flysystem\AwsS3v3\AwsS3Adapter as S3Adapter;
use League\Flysystem\Cached\CachedAdapter;
use League\Flysystem\Cached\CacheInterface;
use League\Flysystem\Cached\Storage\Memory as MemoryStore;
use League\Flysystem\Filesystem as Flysystem;
use League\Flysystem\FilesystemInterface;

class GoogleCloudStorageServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     */
    public function boot()
    {
        $factory = $this->app->make('filesystem');
        /* @var FilesystemManager $factory */
        $factory->extend(
            'gcs',
            function ($app, array $config) {
                $config['endpoint'] = $config['endpoint'] ?? "https://storage.googleapis.com";
                $config['base_url'] = $config['base_url'] ?? "https://storage.googleapis.com";
                $config['region']   = $config['region'] ?? "none";

                $s3Config = $this->formatS3Config($config);
                $root     = $s3Config['root'] ?? null;

                $options = $config['options'] ?? [];

                return $this->adapt(
                    $this->createFlysystem(
                        new S3Adapter(new S3Client($s3Config), $s3Config['bucket'], $root, $options),
                        $config
                    )
                );
            }
        );
    }

    protected function formatS3Config(array $config)
    {
        $config += ['version' => 'latest'];

        if (!empty($config['key']) && !empty($config['secret'])) {
            $config['credentials'] = array_intersect_key($config, array_flip(['key', 'secret', 'token']));
        }

        return $config;
    }

    /**
     * Adapt the filesystem implementation.
     *
     * @param FilesystemInterface $filesystem
     *
     * @return Filesystem
     */
    protected function adapt(FilesystemInterface $filesystem)
    {
        return new FilesystemAdapter($filesystem);
    }

    /**
     * Create a Flysystem instance with the given adapter.
     *
     * @param AdapterInterface $adapter
     * @param array            $config
     *
     * @return FilesystemInterface
     */
    protected function createFlysystem(AdapterInterface $adapter, array $config)
    {
        $cache = $config["cache"] ?? null;

        $config = array_intersect_key($config, array_flip((array) ['visibility', 'disable_asserts', 'url']));
        if ($cache) {
            $adapter = new CachedAdapter($adapter, $this->createCacheStore($cache));
        }

        return new Flysystem($adapter, count($config) > 0 ? $config : null);
    }

    /**
     * Create a cache store instance.
     *
     * @param mixed $config
     *
     * @throws InvalidArgumentException
     *
     * @return CacheInterface
     */
    protected function createCacheStore($config)
    {
        if ($config === true) {
            return new MemoryStore();
        }

        return new Cache(
            $this->app['cache']->store($config['store']),
            $config['prefix'] ?? 'flysystem',
            $config['expire'] ?? null
        );
    }

    /**
     * Register bindings in the container.
     */
    public function register()
    {
        //
    }
}
