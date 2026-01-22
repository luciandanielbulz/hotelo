<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use App\Models\Order;

class ContactController extends Controller
{
    /**
     * Zeigt das Kontaktformular
     */
    public function show(Request $request)
    {
        $plan = $request->query('plan', '');
        $planName = '';
        $planPrice = '';
        
        if ($plan === 'starter') {
            $planName = 'Starter';
            $planPrice = '4,99€/Monat';
        } elseif ($plan === 'enterprise') {
            $planName = 'Enterprise';
            $planPrice = '6,99€/Monat';
        }
        
        return view('contact.form', [
            'selectedPlan' => $plan,
            'planName' => $planName,
            'planPrice' => $planPrice,
        ]);
    }

    /**
     * Verarbeitet das Kontaktformular
     */
    public function submit(Request $request)
    {
        // reCAPTCHA Validierung
        if (config('services.recaptcha.secret_key')) {
            $recaptchaResponse = $request->input('g-recaptcha-response');
            
            // Debug-Logging (nur in Development)
            if (config('app.debug')) {
                \Log::info('reCAPTCHA Debug', [
                    'token_present' => !empty($recaptchaResponse),
                    'token_length' => $recaptchaResponse ? strlen($recaptchaResponse) : 0,
                    'site_key_set' => !empty(config('services.recaptcha.site_key')),
                    'secret_key_set' => !empty(config('services.recaptcha.secret_key')),
                ]);
            }
            
            if (!$recaptchaResponse || empty($recaptchaResponse)) {
                \Log::warning('reCAPTCHA Token fehlt', [
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ]);
                return back()
                    ->withInput()
                    ->withErrors(['recaptcha' => 'Bitte bestätigen Sie, dass Sie kein Roboter sind. Das reCAPTCHA konnte nicht geladen werden. Bitte laden Sie die Seite neu und versuchen Sie es erneut.']);
            }
            
            $recaptchaResult = $this->verifyRecaptcha($recaptchaResponse, $request->ip());
            
            // Debug-Logging der Antwort
            if (config('app.debug')) {
                \Log::info('reCAPTCHA Verification Result', [
                    'success' => $recaptchaResult['success'] ?? false,
                    'score' => $recaptchaResult['score'] ?? null,
                    'error_codes' => $recaptchaResult['error-codes'] ?? [],
                ]);
            }
            
            if (!$recaptchaResult['success']) {
                $errorCodes = $recaptchaResult['error-codes'] ?? [];
                \Log::warning('reCAPTCHA Validierung fehlgeschlagen', [
                    'ip' => $request->ip(),
                    'error_codes' => $errorCodes,
                ]);
                
                $errorMessage = 'reCAPTCHA-Validierung fehlgeschlagen. ';
                if (in_array('invalid-input-secret', $errorCodes)) {
                    $errorMessage .= 'Ungültiger Secret Key. Bitte kontaktieren Sie den Administrator.';
                } elseif (in_array('timeout-or-duplicate', $errorCodes)) {
                    $errorMessage .= 'Der Token ist abgelaufen. Bitte laden Sie die Seite neu und versuchen Sie es erneut.';
                } else {
                    $errorMessage .= 'Bitte versuchen Sie es erneut.';
                }
                
                return back()
                    ->withInput()
                    ->withErrors(['recaptcha' => $errorMessage]);
            }
            
            // Optional: Score-Prüfung (reCAPTCHA v3 gibt einen Score von 0.0 bis 1.0 zurück)
            if (isset($recaptchaResult['score']) && $recaptchaResult['score'] < 0.5) {
                \Log::warning('reCAPTCHA Score zu niedrig', [
                    'ip' => $request->ip(),
                    'score' => $recaptchaResult['score'],
                ]);
                return back()
                    ->withInput()
                    ->withErrors(['recaptcha' => 'Ihre Anfrage wurde als verdächtig eingestuft. Bitte versuchen Sie es erneut.']);
            }
        }
        
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'company' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:255'],
            'street' => ['required', 'string', 'max:255'],
            'postal_code' => ['required', 'string', 'max:20'],
            'city' => ['required', 'string', 'max:255'],
            'country' => ['required', 'string', 'size:2', 'in:AT'],
            'uid_number' => ['nullable', 'string', 'max:255'],
            'plan' => ['nullable', 'string', 'in:starter,enterprise'],
            'is_kleinunternehmer' => ['nullable', 'boolean'],
            'message' => ['nullable', 'string', 'max:2000'],
            'privacy' => ['required', 'accepted'],
            'binding_order' => ['required', 'accepted'],
        ]);

        try {
            // Input Sanitization - HTML-Tags entfernen und trimmen
            $sanitized = [
                'name' => strip_tags(trim($validated['name'])),
                'email' => filter_var(trim($validated['email']), FILTER_SANITIZE_EMAIL),
                'company' => $validated['company'] ? strip_tags(trim($validated['company'])) : null,
                'phone' => $validated['phone'] ? strip_tags(trim($validated['phone'])) : null,
                'street' => strip_tags(trim($validated['street'])),
                'postal_code' => strip_tags(trim($validated['postal_code'])),
                'city' => strip_tags(trim($validated['city'])),
                'country' => $validated['country'],
                'uid_number' => $validated['uid_number'] ? strtoupper(strip_tags(trim($validated['uid_number']))) : null,
                'plan' => $validated['plan'] ?? null,
                'is_kleinunternehmer' => $validated['is_kleinunternehmer'] ?? false,
                'message' => $validated['message'] ? strip_tags(trim($validated['message'])) : null,
                'recaptcha_token' => substr($request->input('g-recaptcha-response', ''), 0, 1000), // Begrenzen
                'ip_address' => $request->ip(),
                'status' => 'pending',
            ];
            
            // Bestellung in Datenbank speichern
            $order = Order::create($sanitized);
            
            // Logging für Debugging
            Log::info('Bestellung erhalten', ['order_id' => $order->id, 'email' => $order->email]);
            
            // Hier könnte eine E-Mail versendet werden
            // Mail::to(config('mail.from.address'))->send(new OrderNotificationMail($order));
            // Mail::to($order->email)->send(new OrderConfirmationMail($order));

            return redirect()->route('contact.thank-you')
                           ->with('success', 'Vielen Dank für Ihre Bestellung! Wir werden uns in Kürze bei Ihnen melden.');
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

    /**
     * Verifiziert das reCAPTCHA-Token
     */
    private function verifyRecaptcha($token, $ip = null)
    {
        $secretKey = config('services.recaptcha.secret_key');
        
        if (!$secretKey) {
            return ['success' => false];
        }
        
        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => $secretKey,
            'response' => $token,
            'remoteip' => $ip,
        ]);
        
        return $response->json();
    }
}

