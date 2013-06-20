<?php

namespace cbednarski\souschef;


class Environment
{
    private $name;
    private $description;
    private $data;

    public static function createFromJson($json)
    {
        $env = new self();
        $env->data = json_decode($json);
        $env->name = $env->data->name;
        $env->description = $env->data->description;
        return $env;
    }

    public static function createFromFile($file)
    {
        if (!is_readable($file)) {
            throw new \Exception('Unable to read ' . $file);
        }

        return self::createFromJson(file_get_contents($file));
    }

    public function __construct()
    {
        // hi
    }

    public function merge($data)
    {
        $name = $this->name;
        $description = $this->description;

        $this->data = self::nestedObjectMerge($this->data, $data);

        $this->data->name = $name;
        $this->data->description = $description;

        return $this;
    }

    public static function nestedObjectMerge($thing1, $thing2)
    {
//        $thing1
        return $thing1;
    }

    public function mergeFile($file)
    {
        if (!is_readable($file)) {
            throw new \Exception('Unable to read ' . $file);
        }

        return self::merge(file_get_contents($file));
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }


}