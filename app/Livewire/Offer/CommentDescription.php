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
        $this->validate([
            'comment' => 'string',
            'description' => 'string'
        ]);

        $offer = Offers::findOrFail($this->offerId);
        $offer->comment = $this->comment;
        $offer->description = $this->description;
        $offer->save();

        $this->dispatch('comment-updated', [
            'message' => 'Zusatzinformationen erfolgreich aktualisiert.'
        ]);

        // Debugging hinzufügen
        \Log::info('Daten aktualisiert:', [
            'comment' => $this->comment,
            'description' => $this->description
        ]);

        $this->loadData($this->offerId);
    }

    public function render()
    {
        return view('livewire.offer.comment-description', [
            'details' => $this->details, // Überprüfen, ob $details gefüllt ist
        ]);
    }
}
