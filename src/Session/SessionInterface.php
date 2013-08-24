<?php
namespace Session;

interface SessionInterface
{
    public function set($key, $value);
    public function get($key);
    public function id();
    public function regenerate($delete);
    public function destroy();
    public function delete($key);
}