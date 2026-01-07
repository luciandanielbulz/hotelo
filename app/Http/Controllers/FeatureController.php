<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FeatureController extends Controller
{
    /**
     * Zeigt die Feature-Detailseite für Rechnungen
     */
    public function invoices()
    {
        return view('features.invoices');
    }

    /**
     * Zeigt die Feature-Detailseite für Angebote
     */
    public function offers()
    {
        return view('features.offers');
    }

    /**
     * Zeigt die Feature-Detailseite für Kundenverwaltung
     */
    public function customers()
    {
        return view('features.customers');
    }

    /**
     * Zeigt die Feature-Detailseite für PDF-Export
     */
    public function pdfs()
    {
        return view('features.pdfs');
    }

    /**
     * Zeigt die Feature-Detailseite für Direkten Versand
     */
    public function sending()
    {
        return view('features.sending');
    }

    /**
     * Zeigt die Feature-Detailseite für Dashboard & Analyse
     */
    public function analytics()
    {
        return view('features.analytics');
    }

    /**
     * Zeigt die "Über Uns" Seite
     */
    public function about()
    {
        return view('about');
    }

    /**
     * Zeigt die Impressum-Seite
     */
    public function impressum()
    {
        return view('legal.impressum');
    }

    /**
     * Zeigt die Datenschutz-Seite
     */
    public function privacy()
    {
        return view('legal.privacy');
    }

    /**
     * Zeigt die Cookie-Richtlinie
     */
    public function cookies()
    {
        return view('legal.cookies');
    }
}

