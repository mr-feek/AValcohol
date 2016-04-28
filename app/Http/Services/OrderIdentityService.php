<?php

namespace App\Http\Services;

use App\Http\Services\OrderIdentity\Factory;
use Exception;
use OrderIdentity\Model\Io\Writer;
use App\Http\Services\OrderIdentity\Factory;
use App\Http\Services\OrderIdentity\Domain\OrderIdentity;
use App\Http\Services\OrderIdentity\Domain\Image;
use App\Http\Services\OrderIdentity\Domain\SvgData;

class OrderIdentityService extends BaseService
{
    protected $factory;
    const DIRECTORY = PNG_DIRECTORY;
    const EXTENSION = EXTENSION;
    
    public function __construct()
    {
        $this->factory = new Factory();
    }

    private function validate(array $data)
    {
        $params = ['photo', 'signature', 'order_id'];
        foreach($params as $param) {
            if(!array_key_exists($param, $data)) {
                throw new Exception('All required order identity parameters are not available.');
            }
        }
    }
    /**
     * Records an order identity entry
     * @param $data
     * @return mixed
     */
    public function record($data)
    {
        $this->validate($data);
        $image = new Image($data['photo'], self::EXTENSION);
        $signature = new SvgData($data['signature']);
        $orderIdentity = new OrderIdentity((int)$data['order_id'], $image, $signature);
        $writer = new Writer(self::DIRECTORY, $this->factory->getDbWriter(), $this->factory->getStreamWriter());
        return $writer->save($orderIdentity);
    }
}
