<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\MainCategory;
use App\Models\CourseCategory;
use App\Models\CourseOverview;
use App\Models\Section;

class Course extends Model
{
    use HasFactory;

      protected $table ="courses";

    public function main_category(){
      return $this->belongsTo(MainCategory::class, 'main_category_id');
    }

    public function course_category(){
      return $this->belongsTo(CourseCategory::class, 'course_category_id');
    }
    public function course_details(){

     return $this->belongsTo(CourseOverview::class,'id','course_id','section_id');
    }
    public function sections(){

     return $this->hasMany(Section::class);
    }
    public function lessons(){

     return $this->hasMany(Lesson::class,'course_id','id');
    }



}
