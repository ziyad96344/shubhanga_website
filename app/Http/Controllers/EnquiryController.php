<?php

namespace App\Http\Controllers;

use App\Models\Enquiry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;

/**
 * Renders and handles every website form defined in config/forms.php.
 */
class EnquiryController extends Controller
{
    public function show(string $form)
    {
        return view('forms.show', [
            'key' => $form,
            'form' => $this->definition($form),
        ]);
    }

    public function store(Request $request, string $form)
    {
        $definition = $this->definition($form);

        // Honeypot: real visitors never fill the hidden "website" field.
        if (filled($request->input('website'))) {
            return redirect()->route('forms.show', $form)->with('sent', true);
        }

        $rules = collect($definition['fields'])->map(function (array $field) {
            $rules = explode('|', $field['rules']);
            if ($field['type'] === 'select' && ! empty($field['options'])) {
                $rules[] = Rule::in($field['options']);
            }

            return $rules;
        })->all();

        $data = $request->validate($rules, [
            'required' => 'Please fill in this field.',
            'email' => 'Enter a valid email address.',
            'min.string' => 'Enter at least :min characters.',
            'max.string' => 'Keep this under :max characters.',
            'integer' => 'Enter a whole number.',
            'in' => 'Choose one of the options.',
            'mimes' => 'Upload a PDF or Word file.',
            'max.file' => 'The file must be under 5 MB.',
        ]);

        // Store uploaded files privately; keep only the path in the record.
        foreach ($definition['fields'] as $name => $field) {
            if ($field['type'] === 'file' && $request->hasFile($name)) {
                $data[$name] = $request->file($name)->store('enquiries/'.$form);
            }
        }

        $enquiry = Enquiry::create([
            'type' => $form,
            'name' => $data['name'],
            'email' => $data['email'] ?? null,
            'phone' => $data['phone'] ?? null,
            'data' => $data,
            'ip' => $request->ip(),
        ]);

        $this->notify($enquiry, $definition);

        return redirect()->route('forms.show', $form)->with('sent', true);
    }

    /** Footer newsletter signup. Stored as an enquiry of type "newsletter". */
    public function newsletter(Request $request)
    {
        $data = $request->validate(['email' => ['required', 'email', 'max:160']], [
            'required' => 'Enter your email address.',
            'email' => 'Enter a valid email address.',
        ]);

        Enquiry::firstOrCreate(
            ['type' => 'newsletter', 'email' => strtolower($data['email'])],
            ['name' => strtolower($data['email']), 'data' => ['email' => strtolower($data['email'])], 'ip' => $request->ip()],
        );

        return $request->expectsJson()
            ? response()->json(['message' => 'Thanks, you are subscribed.'])
            : back()->with('subscribed', true);
    }

    private function definition(string $form): array
    {
        return config("forms.$form") ?? abort(404);
    }

    /**
     * Email the office a plain summary. With MAIL_MAILER=log (the local
     * default) it is written to storage/logs instead of being sent.
     */
    private function notify(Enquiry $enquiry, array $definition): void
    {
        $lines = collect($enquiry->data)
            ->map(fn ($value, $key) => ($definition['fields'][$key]['label'] ?? $key).': '.$value)
            ->implode("\n");

        try {
            Mail::raw($lines, function ($message) use ($enquiry, $definition) {
                $message->to(config('site.contact.email'))
                    ->subject('New enquiry: '.strip_tags($definition['title']).' from '.$enquiry->name);
                if ($enquiry->email) {
                    $message->replyTo($enquiry->email, $enquiry->name);
                }
            });
        } catch (\Throwable $e) {
            // The enquiry is already saved; a mail failure must not lose it.
            Log::warning('Enquiry mail failed', ['id' => $enquiry->id, 'error' => $e->getMessage()]);
        }
    }
}
