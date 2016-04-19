<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\DeliveryZone\Point;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

/**
 * App\Models\DeliveryZone
 *
 * @property integer $id
 * @property string $name
 * @property array $points
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */

class DeliveryZone extends Model
{
    //Default is read
    protected $table = self::READ_TABLE;
    
    //Table for writing
    const WRITE_TABLE = 'delivery_zones';
    
    //View for reading
    const READ_TABLE = 'delivery_zones_astext';
    
    protected $guarded = [];
    
    public static function boot()
    {
        parent::boot();
        //Register save function
        $conversion = function($zone) {
            //Convert points to location value
            if($zone->isDirty('points')) {
                static::convertPoints($zone->attributes);
            }
        };
        parent::saving($conversion);
    }
    
    /**
     * Determines whether or not this delivery zone contains the given point
     * @param Point $point Description
     * @return bool
     */
    public static function containsPoint(Point $point)
    {
        $latitude =  $point->latitude;
        $longitude = $point->longitude;
		$where = "ST_CONTAINS(location, POINT({$latitude}, {$longitude}))";
        $results = static::whereRaw($where)->get();

		return $results->count() > 0 ? true : false;
    }
    
    /**
     *
     * @param Point $point
     * @return bool
     */
    public function doesContainPoint(Point $point)
    {
        static::validatePolygon($points = $this->points);
        return static::pointInPolygon($point, is_array($points) ? $points : array());
    }
    
    //Custom mutator
    public function setPointsAttribute($points)
    {
        $this->attributes['points'] = static::getPoints($points);
    }
    
    public function addPoint(Point $point)
    {
        $this->attributes['points'][] = $point;
    }
    
    public function deletePoint(Point $point)
    {
        if(($key = array_search($point, $this->attributes['points'])) !== false) {
            unset($this->attributes['points'][$key]);
        }
    }
    
    private static function validatePolygon(array $points)
    {
        if(count($points) < 2) {
            throw new FormatException('Polygon must have more than 2 points.');
        }
    }
    
    //Overridden to properly hydrate points property when retrieved from db
    public function setRawAttributes(array $attributes, $sync = false) 
    {
        if(array_key_exists('points', $attributes)) {
            $attributes['points'] = static::getPoints($attributes['points']);
        }
        parent::setRawAttributes($attributes, $sync);
    }
    
    private static function getPoints($points)
    {
        if(is_array($points)) {
            return $points;
        } elseif(is_string($points)) {
            return static::parsePolygon($points);
        }
    }
    
    //Alias
    public function setPolygon($points)
    {
        return $this->setPoints($points);
    }
    
    //Parses points into array from MySQL POLYGON() function
    public static function parsePolygon($polygon)
    {
        if(substr($polygon,0,9) === 'POLYGON((') {
            $pointsStr = substr($polygon, 9, -2);
            $pointsStrs = explode(',', $pointsStr);
            $callback = function($value) {
                $coords = explode(' ', $value);
                return new Point((double) $coords[0], (double) $coords[1]);
            };
            $points = array_map($callback, $pointsStrs);
            return array_unique($points);
        } else {
            return array();
        }
    }

    private static function convertPoints(array &$attributes)
    {
        if(array_key_exists('points', $attributes)) {
            unset($attributes['location']);
            $attributes['location'] = DB::raw(static::getPolygonQueryString($attributes['points']));
            unset($attributes['points']);
        }
    }
    
    private static function getPolygonQueryString($points)
    {
        static::validatePolygon($points);
        array_push($points, $points[0]);
        $pointsStr = implode(',', $points);
        $polygonQuery = "ST_GEOMFROMTEXT(\"POLYGON(({$pointsStr}))\")";
        return $polygonQuery;
    }
    
    /**
     * @param Point $p
     * @param array polygon Array of type Point
     * @url http://stackoverflow.com/questions/14818567/point-in-polygon-algorithm-giving-wrong-results-sometimes
     */
    public static function pointInPolygon(Point $p, array $polygon)
    {
        $c = 0;
        $p1 = $polygon[0];
        $n = count($polygon);

        for ($i=1; $i<=$n; $i++) {
            $p2 = $polygon[$i % $n];
            if ($p->longitude > min($p1->longitude, $p2->longitude)
                && $p->longitude <= max($p1->longitude, $p2->longitude)
                && $p->latitude <= max($p1->latitude, $p2->latitude)
                && $p1->longitude != $p2->longitude) {
                    $xinters = ($p->longitude - $p1->longitude) * 
                            ($p2->latitude - $p1->latitude) 
                            / ($p2->longitude - $p1->longitude) + $p1->latitude;
                    if ($p1->latitude == $p2->latitude || $p->latitude <= $xinters) {
                        $c++;
                    }
            }
            $p1 = $p2;
        }
        // if the number of edges we passed through is even, then it's not in the poly.
        return $c%2!=0;
    }
    
    //Convert to write table before save and revert back for reads
    public function save(array $options = array())
    {
        $this->table = self::WRITE_TABLE;
        parent::save($options);
        $this->table = self::READ_TABLE;
    }
}
