<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ReportCatalogue;

class ReportCatalogueController extends Controller
{
    /**
     * Display Listing
     */
    public function index()
    {
        $reports = ReportCatalogue::latest()->paginate(20);

        return view(
            'report_catalogues.index',
            compact('reports')
        );
    }

    /**
     * Show Create Form
     */
    public function create()
    {
        return view('report_catalogues.create');
    }

    /**
     * Store Data
     */
    public function store(Request $request)
    {
        $request->validate([

            'report_name' => 'required',
            'category'    => 'required',
            'frequency'   => 'required',
        ]);

        $lastId = ReportCatalogue::max('id') + 1;

        $reportCode = 'RPT-' .
            str_pad($lastId, 4, '0', STR_PAD_LEFT);

        ReportCatalogue::create([

            'report_code'    => $reportCode,
            'report_name'    => $request->report_name,
            'category'       => $request->category,
            'frequency'      => $request->frequency,
            'description'    => $request->description,
            'kpis_metrics'   => $request->kpis_metrics,
            'source_tables'  => $request->source_tables,
            'primary_filter' => $request->primary_filter,
            'default_sort'   => $request->default_sort,
            'output_format'  => $request->output_format,
            'priority'       => $request->priority,
            'status'         => $request->status ?? 1,
        ]);

        return redirect()
            ->route('report-catalogues.index')
            ->with(
                'success',
                'Report Created Successfully'
            );
    }

    /**
     * Edit Form
     */
    public function edit($id)
    {
        $report = ReportCatalogue::findOrFail($id);

        return view(
            'report_catalogues.edit',
            compact('report')
        );
    }

    /**
     * Update
     */
    public function update(Request $request, $id)
    {
        $request->validate([

            'report_name' => 'required',
            'category'    => 'required',
            'frequency'   => 'required',
        ]);

        $report = ReportCatalogue::findOrFail($id);

        $report->update([

            'report_name'    => $request->report_name,
            'category'       => $request->category,
            'frequency'      => $request->frequency,
            'description'    => $request->description,
            'kpis_metrics'   => $request->kpis_metrics,
            'source_tables'  => $request->source_tables,
            'primary_filter' => $request->primary_filter,
            'default_sort'   => $request->default_sort,
            'output_format'  => $request->output_format,
            'priority'       => $request->priority,
            'status'         => $request->status ?? 1,
        ]);

        return redirect()
            ->route('report-catalogues.index')
            ->with(
                'success',
                'Report Updated Successfully'
            );
    }

    /**
     * Delete
     */
    public function destroy($id)
    {
        $report = ReportCatalogue::findOrFail($id);

        $report->delete();

        return redirect()
            ->route('report-catalogues.index')
            ->with(
                'success',
                'Report Deleted Successfully'
            );
    }
}