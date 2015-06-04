<?php

namespace Gaufrette\Adapter;

use Gaufrette\Core\Adapter;
use Gaufrette\Core\Adapter\CanListKeys;
use Gaufrette\Core\Adapter\KnowsChecksum;
use Gaufrette\Core\Adapter\KnowsContent;
use Gaufrette\Core\Adapter\KnowsLastAccess;
use Gaufrette\Core\Adapter\KnowsLastModification;
use Gaufrette\Core\Adapter\KnowsMetadata;
use Gaufrette\Core\Adapter\KnowsMimeType;
use Gaufrette\Core\Adapter\KnowsSize;

class InMemory implements Adapter, CanListKeys, KnowsContent, KnowsChecksum, KnowsMimeType, KnowsSize, KnowsMetadata, KnowsLastAccess, KnowsLastModification
{
    /**
     * @var array[]
     */
    private $files;

    /**
     * @param string[] $files
     */
    public function __construct(array $files = array())
    {
        $this->files = array();
        $this->setFiles($files);
    }

    /**
     * {@inheritdoc}
     */
    public function readContent($key)
    {
        return $this->files[$key]['content'];
    }

    /**
     * {@inheritdoc}
     */
    public function writeContent($key, $content)
    {
        $this->files[$key]['content'] = $content;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function delete($key)
    {
        unset($this->files[$key]);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function exists($key)
    {
        return array_key_exists($key, $this->files);
    }

    /**
     * {@inheritdoc}
     */
    public function listKeys($prefix = '')
    {
        $keys = array_keys($this->files);
        sort($keys);

        return array_values(array_filter($keys, function ($e) use ($prefix) {
            return empty($prefix) || 0 === strpos($e, $prefix);
        }));
    }

    /**
     * {@inheritdoc}
     */
    public function readChecksum($key)
    {
        return md5($this->files[$key]['content']);
    }

    /**
     * {@inheritdoc}
     */
    public function readMetadata($key)
    {
        return $this->files[$key]['metadata'];
    }

    /**
     * {@inheritdoc}
     */
    public function writeMetadata($key, array $metadata)
    {
        $this->files[$key]['metadata'] = $metadata;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function readMimeType($key)
    {
        $content = $this->files[$key]['content'];
        $info    = new \finfo(FILEINFO_MIME_TYPE);

        return $info->buffer($content);
    }

    /**
     * {@inheritdoc}
     */
    public function readSize($key)
    {
        $content = $this->files[$key]['content'];

        return mb_strlen($content, '8bit');
    }

    /**
     * {@inheritdoc}
     */
    public function readLastAccess($key)
    {
        return $this->files[$key]['last access'];
    }

    /**
     * {@inheritdoc}
     */
    public function writeLastAccess($key, $time)
    {
        $this->files[$key]['last access'] = $time;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function readLastModification($key)
    {
        return $this->files[$key]['last modification'];
    }

    /**
     * {@inheritdoc}
     */
    public function writeLastModification($key, $time)
    {
        $this->files[$key]['last modification'] = $time;

        return $this;
    }

    /**
     * @param array $files
     *
     * @return InMemory
     */
    public function setFiles(array $files)
    {
        foreach ($files as $key => $data) {
            $this->setFile($key, $data);
        }

        return $this;
    }

    /**
     * @param string $key
     * @param array  $data
     *
     * @return InMemory
     */
    public function setFile($key, array $data)
    {
        $legacy = array(
            'content'           => null,
            'metadata'          => array(),
            'last access'       => 0,
            'last modification' => 0,
        );

        if (array_key_exists($key, $this->files)) {
            $legacy = $this->files[$key];
        }

        $this->files[$key] = array_merge($legacy, $data);

        return $this;
    }
}
