<?php

namespace OrderIdentity\Model\Io;

class Writer
{
    protected $dbWriter;
    protected $streamWriter;

    public function __construct($directory, DbWriter $dbWriter, StreamWriter $streamWriter)
    {
        $this->directory = $directory;
        $this->dbWriter = $dbWriter;
        $this->streamWriter = $streamWriter;
        $this->writer->setOverwrite(false);
    }

    public function save(OrderIdentity $order)
    {
        $base = $this->mapper->save($order);
        $filename = $base.$order->getImage()->getExtension();
        $this->writer->write($order->getImage()->getData(), $filename);
        return $base;
    }
}
