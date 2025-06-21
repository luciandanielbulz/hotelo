<?php

namespace App\Livewire\Offer;
use App\Models\Offers;

use Livewire\Component;

class CommentDescription extends Component
{
    public $offerId;
    public $comment;
    public $description;
    public $message;
    public $details;

    public function mount($offerId)
    {
        $this->offerId = $offerId;
        $this->loadData($offerId);
    }

    public function loadData($offerId)
    {
        $this->details = Offers::findOrFail($offerId);

        $this->comment = $this->details->comment;
        $this->description = $this->details->description;
    }

    public function updateCommentDescription()
    {
        try {
            $this->validate([
                'comment' => 'nullable|string',
                'description' => 'nullable|string'
            ]);

            $offer = Offers::findOrFail($this->offerId);
            $offer->comment = $this->comment;
            $offer->description = $this->description;
            $offer->save();

            $this->message = 'Zusatzinformationen erfolgreich aktualisiert.';

            // Event-Dispatch entfernt - Erfolgsmeldung wird jetzt nur noch in der Komponente angezeigt

            // Debugging hinzufügen
            \Log::info('Daten aktualisiert:', [
                'comment' => $this->comment,
                'description' => $this->description
            ]);

            $this->loadData($this->offerId);
            
        } catch (\Exception $e) {
            \Log::error('Fehler beim Speichern der Zusätzlichen Informationen: ' . $e->getMessage(), [
                'exception' => $e,
                'offerId' => $this->offerId,
            ]);
            
            $this->message = 'Fehler beim Speichern: ' . $e->getMessage();
        }
    }

    public function render()
    {
        return view('livewire.offer.comment-description', [
            'details' => $this->details, // Überprüfen, ob $details gefüllt ist
        ]);
    }
}
