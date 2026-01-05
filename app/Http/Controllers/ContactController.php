<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class ContactController extends Controller
{
    /**
     * Zeigt das Kontaktformular
     */
    public function show()
    {
        return view('contact.form');
    }

    /**
     * Verarbeitet das Kontaktformular
     */
    public function submit(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'company' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:255',
            'message' => 'required|string|max:2000',
        ]);

        try {
            // Hier könnte eine E-Mail versendet werden
            // Mail::to(config('mail.from.address'))->send(new ContactFormMail($validated));
            
            // Logging für Debugging
            Log::info('Kontaktanfrage erhalten', $validated);

            return redirect()->route('contact.thank-you')
                           ->with('success', 'Vielen Dank für Ihre Anfrage! Wir werden uns in Kürze bei Ihnen melden.');
        } catch (\Exception $e) {
            Log::error('Fehler beim Verarbeiten der Kontaktanfrage', [
                'error' => $e->getMessage(),
                'data' => $validated
            ]);

            return back()
                ->withInput()
                ->withErrors(['error' => 'Es ist ein Fehler aufgetreten. Bitte versuchen Sie es erneut oder kontaktieren Sie uns direkt.']);
        }
    }

    /**
     * Zeigt die Dankeseite
     */
    public function thankYou()
    {
        return view('contact.thank-you');
    }
}

