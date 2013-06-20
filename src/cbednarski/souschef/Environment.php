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

        $this->data = self::nestedObjectMerge($this->data, json_decode($data));

        $this->data->name = $name;
        $this->data->description = $description;

        return $this;
    }

    public static function nestedObjectMerge($thing1, $thing2)
    {

        foreach((array) $thing2 as $key => $var) {
            if(is_array($thing1)) {
                if(self::isArrayish($var)) {
                    self::nestedObjectMerge($thing1[$key], $var);
                } else {
                    $thing1[$key] = $var;
                }
                self::nestedObjectMerge($thing1[$key], $var);
            } elseif (is_object($thing1)) {
                if(self::isArrayish($var)) {
                    self::nestedObjectMerge($thing1->$key, $var);
                } else {
                    $thing1->$key = $var;
                }
            }
        }

        return $thing1;
    }

    public static function isArrayish($thing) {
        return is_array($thing) || is_object($thing);
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

    public function getDataAsJson()
    {
        return json_encode($this->data);
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

    /**
     * @param mixed $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }
}