<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Imagegallery extends Model
{
    use HasFactory;
    protected $table = 'image_galleries';
    protected $fillable = ['title','image','postid'];



public function user()
{
    return $this->belongsTo('App\Models\User', 'postid', 'id');
}


public function toArray(){
    $data = parent::toArray();
    $data['edited_by']=$this->user;
    $data['edited_by']->makeHidden('email_verified_at');
    $data['edited_by']->makeHidden('created_at');
    $data['edited_by']->makeHidden('updated_at');
    $data['edited_by']->makeHidden('email');
    return $data;
}





}

