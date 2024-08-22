<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanySetting extends Model
{
    use HasFactory;
    protected $fillable = [
      'company_name',
      'logo',
      'phone_one',
      'phone_two',
      'address',
      'email',
      'facebook',
      'youtube',
      'linkedin',
    ];
}