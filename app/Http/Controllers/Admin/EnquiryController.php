<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Enquiry;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/** Messages from every website form: specimen copies, bulk orders, distributors, authors, contact, newsletter. */
class EnquiryController extends Controller
{
    public const STATUSES = ['new' => 'New', 'in_progress' => 'In progress', 'done' => 'Done'];

    public function index(Request $request)
    {
        $query = Enquiry::query();

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $term = '%'.$request->search.'%';
            $query->where(fn ($q) => $q->where('name', 'like', $term)->orWhere('email', 'like', $term)->orWhere('phone', 'like', $term));
        }

        return view('admin.enquiries.index', [
            'enquiries' => $query->latest()->paginate(20)->withQueryString(),
            'types' => $this->types(),
            'counts' => Enquiry::selectRaw('type, COUNT(*) as c')->groupBy('type')->pluck('c', 'type'),
        ]);
    }

    public function show(Enquiry $enquiry)
    {
        if ($enquiry->status === 'new') {
            $enquiry->update(['status' => 'in_progress']);
        }

        return view('admin.enquiries.show', ['enquiry' => $enquiry, 'types' => $this->types()]);
    }

    public function update(Request $request, Enquiry $enquiry)
    {
        $enquiry->update($request->validate(['status' => ['required', Rule::in(array_keys(self::STATUSES))]]));

        return back()->with('success', 'Marked as '.strtolower(self::STATUSES[$enquiry->status]).'.');
    }

    /** Downloads a file attached to the form (kept on the private disk). */
    public function file(Enquiry $enquiry, string $field)
    {
        $path = $enquiry->data[$field] ?? null;
        abort_unless(is_string($path) && str_starts_with($path, 'enquiries/') && \Illuminate\Support\Facades\Storage::exists($path), 404);

        return \Illuminate\Support\Facades\Storage::download($path);
    }

    public function destroy(Enquiry $enquiry)
    {
        $enquiry->delete();

        return redirect()->route('admin.enquiries.index')->with('success', 'Message deleted.');
    }

    private function types(): array
    {
        return collect(config('forms'))->map(fn ($form) => strip_tags($form['title']))->all() + ['newsletter' => 'Newsletter'];
    }
}
