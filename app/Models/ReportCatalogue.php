<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReportCatalogue extends Model
{
  protected $fillable = [

        'report_code',
        'report_name',
        'category',
        'frequency',
        'description',
        'kpis_metrics',
        'source_tables',
        'primary_filter',
        'default_sort',
        'output_format',
        'priority',
        'status',
    ];
}
