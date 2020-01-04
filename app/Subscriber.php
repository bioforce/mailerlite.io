<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subscriber extends Model
{
    //
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['email', 'address', 'name', 'state'];

    /**
     * List of states
     * 
     * @var array
     */
    protected $states = [
        'A' => 'active',
        'U' => 'unsubscribed',
        'J' => 'junk',
        'B' => 'bounced',
        'N' => 'unconfirmed',
    ];

    /**
     * The accessors to append to the model's array form.
     * 
     * @var array
     */
    protected $appends = ['show_state'];

    /**
     * The fields that belong to the subscriber.
     */
    public function fields()
    {
        return $this->belongsToMany('App\Field', 'subscriber_field')->withPivot('value')->withTimestamps();
    }

    /**
     * Confirm email of Subscriber
     */
    public function confirmed()
    {
        $this->state = 'A';
        $this->save();
        return $this;
    }

    /**
     * Default as unconfirmed Subscriber
     */
    public function unconfirmed()
    {
        $this->state = 'N';
        $this->save();
        return $this;
    }

    /**
     * Get show state
     * @return string
     */
    public function getShowStateAttribute()
    {
        return (array_key_exists($this->state, $this->states))?$this->states[$this->state]:$this->state;
    }
}
