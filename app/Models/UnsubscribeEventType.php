<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UnsubscribeEventType extends Model
{
    protected $table = 'sendportal_unsubscribe_event_types';

    const BOUNCE = 1;
    const COMPLAINT = 2;
    const MANUAL_BY_ADMIN = 3;
    const MANUAL_BY_SUBSCRIBER = 4;
    const END_OF_THE_SUBSCRIPTION = 5;

    public static $types = [
        1 => 'Bounced',
        2 => 'Complained',
        3 => 'Manual by Admin',
        4 => 'Manual by Subscriber',
        5 => 'End of the subscription',
    ];

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * Get the unsubscribe type by ID
     *
     * @param int $id
     * @return mixed
     */
    public static function findById($id): string
    {
        return \Arr::get(static::$types, $id);
    }
}
