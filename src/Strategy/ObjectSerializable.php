<?php

namespace Orangesoft\Throttler\Strategy;

abstract class ObjectSerializable implements \Serializable
{
    public function serialize(): string
    {
        $vars = get_object_vars($this);

        return serialize($vars);
    }

    /**
     * @param string $serialized
     */
    public function unserialize($serialized): void
    {
        $vars = unserialize($serialized, [
            'allowed_classes' => [
                static::class,
            ]
        ]);

        foreach ($vars as $name => $value) {
            $this->{$name} = $value;
        }
    }
}
