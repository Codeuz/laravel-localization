<?php

namespace Cdz\Localization;

use Illuminate\Http\Request;

class LanguageNegotiator
{
    /**
     * @var array
     */
    private $supportedLanguages;

    /**
     * @var Request
     */
    private $request;

    /**
     * LanguageNegotiator constructor.
     * @param array $supportedLanguages
     * @param Request $request
     */
    public function __construct($supportedLanguages, Request $request)
    {
        $this->supportedLanguages = $supportedLanguages;
        $this->request = $request;
    }

    /**
     * LanguageNegotiator negociate
     * @return int|string|null
     */
    public function negotiateLanguage()
    {
        $matches = $this->getMatchesFromAcceptedLanguages();
        foreach ($matches as $key => $q) {
            if (in_array($key, $this->supportedLanguages)) {
                return $key;
            }
        }

        return null;
    }

    /**
     * Return all the accepted languages from the browser.
     *
     * @return array Matches from the header field Accept-Languages
     */
    private function getMatchesFromAcceptedLanguages()
    {
        $matches = [];

        if ($acceptLanguages = $this->request->header('Accept-Language')) {
            $acceptLanguages = explode(',', $acceptLanguages);

            $generic_matches = [];
            foreach ($acceptLanguages as $option) {
                $option = array_map('trim', explode(';', $option));
                $l = $option[0];
                if (isset($option[1])) {
                    $q = (float) str_replace('q=', '', $option[1]);
                } else {
                    $q = null;
                    // Assign default low weight for generic values
                    if ($l == '*/*') {
                        $q = 0.01;
                    } elseif (substr($l, -1) == '*') {
                        $q = 0.02;
                    }
                }
                // Unweighted values, get high weight by their position in the
                // list
                $q = $q ?? 1000 - \count($matches);
                $matches[$l] = $q;

                //If for some reason the Accept-Language header only sends language with country
                //we should make the language without country an accepted option, with a value
                //less than it's parent.
                $l_ops = explode('-', $l);
                array_pop($l_ops);
                while (!empty($l_ops)) {
                    //The new generic option needs to be slightly less important than it's base
                    $q -= 0.001;
                    $op = implode('-', $l_ops);
                    if (empty($generic_matches[$op]) || $generic_matches[$op] > $q) {
                        $generic_matches[$op] = $q;
                    }
                    array_pop($l_ops);
                }
            }
            $matches = array_merge($generic_matches, $matches);

            arsort($matches, SORT_NUMERIC);
        }

        return $matches;
    }
}
