<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Field extends Model
{
    //
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['title', 'type'];

    /**
     * List of types
     * 
     * @var array
     */
    protected $types = [
        'D' => 'date',
        'N' => 'number',
        'S' => 'string',
        'B' => 'boolean',
    ];

    /**
     * The accessors to append to the model's array form.
     * 
     * @var array
     */
    protected $appends = ['show_type'];

    /**
     * Get show value for type
     * 
     * @return string
     */
    public function getShowTypeAttribute()
    {
        return (array_key_exists($this->type, $this->types))?$this->types[$this->type]:$this->type;
    }

}
