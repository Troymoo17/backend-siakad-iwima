<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Kurikulum extends Model {
    protected $table = 'kurikulum'; public $timestamps = false;
    protected $fillable = ['kode_mk','semester','prodi','status','ipk_min','sks_min','grade_min','mk_persyaratan','urutan','updated_by'];
    protected $casts = ['updated_at'=>'datetime','ipk_min'=>'float'];
    public function mataKuliah() { return $this->belongsTo(MataKuliah::class,'kode_mk','kode_mk'); }
}
