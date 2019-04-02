<?php

namespace Pawlox\VideoThumbnail\Facade;

use Illuminate\Support\Facades\Facade;
use  Pawlox\VideoThumbnail\VideoCut;

/**
 * @author     sukhilss <emailtosukhil@gmail.com>
 * @package    Video Thumbnail
 * @version    1.0.0
 */
class VideoCut extends Facade {

    protected static function getFacadeAccessor() {
        return 'VideoCut';
    }

}